<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Config\Database;
use App\Helpers\Sms_helper;

class AuthController extends BaseController
{
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db        = Database::connect ();

    }

    protected function sendResponse( bool $status, string $message, $data = [], int $statusCode = 200 )
    {
        return $this->response->setJSON ( [
            'status'  => $status,
            'message' => $message,
            'data'    => $data
        ] )->setStatusCode ( $statusCode );
    }

    /**
     * User Login (OTP based if phone provided, otherwise verify existing token)
     */
    public function sendOtp()
    {
        $phone = $this->request->getVar ( 'phone' );
        $otp   = random_int ( 1111, 9999 );

        if ( $phone && $otp ) {
            // Verify OTP
            // $user     = $this->userModel->check ( $phone, $otp );
            $response = Sms_helper::sendSms ( $phone, $otp );
            if ( $response != 200 ) {
                return $this->sendResponse ( false, 'OTP not sent', [], 401 );

            }
            return $this->sendResponse ( true, 'OTP Sent successfully' );

        }

        return $this->sendResponse ( false, 'Phone number is required', [], 400 );
    }
    public function verifyOtp()
    {
        $phone = $this->request->getVar ( 'phone' );
        $otp   = $this->request->getVar ( 'otp' );
        if ( $phone && $otp ) {
            // Verify OTP
            $user = $this->userModel->check ( $phone, $otp );
            if ( $user ) {
                $token = bin2hex ( random_bytes ( 16 ) );
                $this->userModel->update ( $user->id, ['auth_token' => $token] );
                $user->auth_token = $token;
                return $this->sendResponse ( true, 'Login successful', $user );
            }
            return $this->sendResponse ( false, 'Invalid phone or OTP', [], 401 );

        }
        return $this->sendResponse ( true, 'Login successful', $user );

    }

    /**
     * Admin Login (Mobile/Email and Password)
     */
    public function adminLogin()
    {
        $login    = $this->request->getVar ( 'login' ); // email or mobile
        $password = $this->request->getVar ( 'password' );

        if ( !$login || !$password ) {
            return $this->sendResponse ( false, 'Login and password are required', [], 400 );
        }

        $employee = $this->db->table ( 'employees' )
            ->where ( 'mobile', $login )
            ->orWhere ( 'email', $login )
            ->get ()
            ->getRow ();

        if ( $employee && password_verify ( $password, $employee->password ) ) {
            // Success
            unset ($employee->password);
            $token = bin2hex ( random_bytes ( 16 ) );
            $this->db->table ( 'employees' )->where ( 'id', $employee->id )->update ( ['auth_token' => $token] );
            $employee->auth_token = $token;
            $employee->user_type  = 'admin';
            return $this->sendResponse ( true, 'Admin login successful', $employee );
        }

        return $this->sendResponse ( false, 'Invalid credentials', [], 401 );
    }

    /**
     * Get Profile
     */
    public function getProfile()
    {
        $userId   = $this->request->getVar ( 'user_id' );
        $userType = $this->request->getVar ( 'user_type' ) ?? 'user';

        if ( !$userId ) {
            return $this->sendResponse ( false, 'User ID is required', [], 400 );
        }

        if ( $userType === 'admin' ) {
            $profile = $this->db->table ( 'employees' )->where ( 'id', $userId )->get ()->getRow ();
            if ( $profile ) unset ($profile->password);
        }
        else {
            $profile = $this->userModel->getUser ( $userId );
        }

        if ( $profile ) {
            return $this->sendResponse ( true, 'Profile retrieved', $profile );
        }

        return $this->sendResponse ( false, 'Profile not found', [], 404 );
    }

    /**
     * Update Profile
     */
    public function updateProfile()
    {
        $userId       = $this->request->getPost ( 'user_id' );
        $userType     = $this->request->getPost ( 'user_type' ) ?? 'user';
        $name         = $this->request->getPost ( 'name' );
        $email        = $this->request->getPost ( 'email' );
        $phone        = $this->request->getPost ( 'phone' );
        $profileImage = $this->request->getFile ( 'profile' );

        if ( !$userId ) {
            return $this->sendResponse ( false, 'User ID is required', [], 400 );
        }

        $updateData = [];
        if ( $name ) $updateData['name'] = $name;
        if ( $email ) $updateData['email'] = $email;

        // Handle profile image upload
        if ( $profileImage && $profileImage->isValid () && !$profileImage->hasMoved () ) {
            $newName = $profileImage->getRandomName ();
            $profileImage->move ( ROOTPATH . 'public/uploads/users/', $newName );
            $updateData['profile'] = $newName;
        }

        if ( $userType === 'admin' ) {
            if ( $phone ) $updateData['mobile'] = $phone;
            $this->db->table ( 'employees' )->where ( 'id', $userId )->update ( $updateData );
        }
        else {
            if ( $phone ) $updateData['phone'] = $phone;
            $updateData['user_id'] = $userId;
            // Map gender if provided
            $updateData['gender']     = $this->request->getPost ( 'gender' ) ?? 'Other';
            $updateData['ip_address'] = $this->request->getIPAddress ();

            // The UserModel expects specific keys for its updateProfile method
            // but we can also use query builder here for simplicity or adapt to UserModel
            $this->db->table ( 'users' )->where ( 'id', $userId )->update ( $updateData );
        }

        return $this->sendResponse ( true, 'Profile updated successfully', $updateData );
    }
}
