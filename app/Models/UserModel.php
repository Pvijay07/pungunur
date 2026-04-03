<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $users;
    protected $address;
    protected $services;
    protected $packages;

    protected $addons;

    public function __construct()
    {
        parent::__construct ();
        $this->db       = \Config\Database::connect ();
        $this->users    = 'users';
        $this->address  = 'user_addresses';
        $this->services = 'services';
        $this->packages = 'packages';
        $this->addons   = 'addons';
    }

    public function create( $data )
    {
        return $this->db->table ( $this->users )
            ->insert ( $data );
    }
    public function getUser( $user_id )
    {
        return $this->db->table ( $this->users )
            ->select ( 'id,name,email,phone,gender,profile,device_token,city,status' )
            ->where ( 'id', $user_id )
            ->get ()
            ->getRow ();
    }
    public function updateToken( $phone, $token )
    {
        return $this->db->table ( $this->users )
            ->set ( 'auth_token', $token )
            ->where ( 'phone', $phone )
            ->update ();
    }
    public function getToken( $token )
    {
        return $this->db->table ( $this->users )
            ->select ( 'id,email,phone,gender,profile' )
            ->where ( 'auth_token', $token )
            ->get ()
            ->getRow ();
    }
    public function checkExists( $phone )
    {
        return $this->db->table ( $this->users )
            ->select ( 'id' )

            ->where ( 'phone', $phone )
            ->get ()
            ->getRow ();
    }
    public function check( $phone, $otp )
    {
        return $this->db->table ( $this->users )
            ->select ( 'id,email,phone,name,device_token' )

            ->where ( 'phone', $phone )
            ->where ( 'otp', $otp )
            ->get ()
            ->getRow ();
    }
    public function checkPhone( $phone )
    {
        return $this->db->table ( $this->users )
            ->select ( 'id' )
            ->where ( 'phone', $phone )
            ->get ()
            ->getRow ();
    }
    public function checkEmail( $email )
    {
        return $this->db->table ( $this->users )
            ->select ( 'id' )
            ->where ( 'email', $email )
            ->get ()
            ->getRow ();
    }
    public function updateOtp( $data )
    {
        return $this->db->table ( $this->users )
            ->update ( ['otp' => $data['otp'], 'updated_at' => gmdate ( 'Y-m-d H:i:s' ), 'ip_address' => $data['ip_address'], 'device_token' => $data['device_token']], ['id' => $data['user_id']] );
    }
    public function removeOtp( $phone )
    {
        return $this->db->table ( $this->users )

            ->where ( 'phone', $phone )
            ->update ( ['otp' => ''] );
    }
    public function updateProfile( $data )
    {
        return $this->db->table ( $this->users )
            ->where ( 'id', $data['user_id'] )
            ->set ( 'name', $data['name'] )
            ->set ( 'email', $data['email'] )
            ->set ( 'phone', $data['phone'] )
            ->set ( 'gender', $data['gender'] )
            ->set ( 'profile', $data['profile'] )
            ->set ( 'ip_address', $data['ip_address'] )
            ->set ( 'updated_at', gmdate ( 'Y-m-d H:i:s' ) )
            ->update ();
    }
    public function addAddress( $data )
    {
        $this->db->table ( $this->address )->insert ( $data );
        return $this->db->insertID ();
    }
    public function updateAddress( $data )
    {
        return $this->db->table ( $this->address )
            ->set ( 'houseno_floor', $data->houseno_floor )
            ->set ( 'building_blockno', $data->building_blockno )
            ->set ( 'landmark_areaname', $data->landmark_areaname )
            ->set ( 'city', $data->city )
            ->set ( 'address', $data->address )
            ->set ( 'state', $data->state )
            ->set ( 'country', $data->country )
            ->set ( 'zip_code', $data->zip_code )
            ->set ( 'latitude', $data->latitude )
            ->set ( 'longitude', $data->longitude )
            ->set ( 'type', $data->type )
            ->set ( 'updated_at', $data->updated_at )
            ->where ( 'id', $data->id )
            ->update ();
    }
    public function deleteAddress( $user_id, $id )
    {
        return $this->db->table ( $this->address )
            ->where ( 'id', $id )
            ->where ( 'user_id', $user_id )
            ->delete ();
    }
    public function getAddress( $user_id )
    {
        return $this->db->table ( $this->address )
            ->where ( 'user_id', $user_id )
            ->get ()
            ->getResult ();
    }
    public function getAddressById( $id )
    {
        return $this->db->table ( $this->address )
            ->where ( 'id', $id )
            ->get ()
            ->getResult ();
    }
    public function getServices()
    {
        return $this->db->table ( $this->services )
            ->select ( 'id,name,icon' )
            ->get ()
            ->getResult ();
    }
    public function getPackages()
    {
        return $this->db->table ( $this->packages )
            ->select ( 'id,package_name,price,duration_days,price_per,is_most_popular,icon' )
            ->get ()
            ->getResult ();
    }
    public function getServicepackages( $service_id )
    {
        return $this->db->table ( $this->packages )
            ->select ( '
            packages.id,
            packages.package_name,
            packages.price,
            packages.sessions,
            packages.duration_days,
            packages.price_per,
            packages.is_most_popular,
            packages.icon,
            packages.color,
            packages.service_id,

            CONCAT_WS(", ",
                GROUP_CONCAT(DISTINCT CASE WHEN TRIM(addons.name) != "" THEN addons.name END ORDER BY addons.name SEPARATOR ", ")
            , packages.included_addons) as addons 
        ' )
            ->join ( 'package_addons', 'package_addons.package_id = packages.id', 'left' )
            ->join ( 'addons', 'package_addons.addon_id = addons.id AND package_addons.is_included = 1', 'left' )
            ->where ( 'packages.service_id', $service_id )
            ->groupBy ( 'packages.id' )
            ->orderBy ( 'packages.id', 'DESC' )
            ->get ()
            ->getResult ();
    }

    public function spTrainingPackages()
    {
        return $this->db->table ( 'sp_training_packages' )
            ->select ( 'id,name,addons' )
            // ->orderBy('id', 'DESC')
            ->get ()
            ->getResult ();
    }
    public function getServiceaddons( $service_id, $package_id = null )
    {
        $query = $this->db->table ( $this->addons )
            ->select ( 'addons.id, addons.name, addons.price' );

        if ( !empty ( $package_id ) ) {
            $query = $query->where ( 'package_id', $package_id );
            $query->join ( 'package_addons', 'package_addons.addon_id = addons.id', 'left' );
            $query->where ( 'package_addons.is_included', false );
        }


        return $query->where ( 'service_id', $service_id )
            ->orderBy ( 'id', 'DESC' )
            ->get ()
            ->getResult ();
    }
    public function getUserNewWalkingBookings( $user_id )
    {
        $builder = $this->db->table ( 'walking_service_bookings wb' )
            ->select ( "
            s.name AS service_name,
            s.id AS service_id,
            wb.type AS booking_type,
            wb.id AS booking_id,
            wb.original_booking_id,
            wb.service_id AS booked_service_id,
            wb.service_frequency,
            wb.walk_duration,
            wb.service_days,
            wb.service_start_date,
            wb.service_end_date,
            wb.preferable_time,
            wb.status,
            wb.user_id,
            p.package_name,
            p.duration_days AS days,
            ua.address,
            ua.houseno_floor,
            ua.building_blockno,
            ua.landmark_areaname,
            ua.city,
            ua.state,
            ua.country,
            ua.zip_code,
            ua.type,
            (SELECT COUNT(*) 
                FROM quotations q 
                WHERE q.booking_id = wb.id 
                  AND q.status = 'New'
            ) AS total_quote
        " )
            ->join ( 'services s', 's.id = wb.service_id', 'left' )
            ->join ( 'user_addresses ua', 'ua.id = wb.address_id', 'left' )
            ->join ( 'packages p', 'p.id = wb.package_id', 'left' )
            ->where ( 'wb.user_id', $user_id )
            ->whereIn ( 'wb.status', ['New', 'Halted'] )
            ->groupBy ( 'wb.id' );

        $walkingBookings = $builder->get ()->getResult ();

        foreach ( $walkingBookings as $booking ) {
            $booking->addons = $this->fetchBookingAddons (
                $booking->original_booking_id ?: $booking->booking_id
            );
        }

        return $walkingBookings;
    }

    public function getUserNewTrainingBookings( $user_id )
    {
        // Training Bookings
        $trainingBookings = $this->db->table ( 'training_service_bookings' )
            ->select ( 'services.name as service_name,services.id as service_id' )
            ->select ( 'packages.package_name as package_name,packages.sessions' )
            ->select ( 'training_service_bookings.id as booking_id, training_service_bookings.service_id as service_id, training_service_bookings.service_start_date, training_service_bookings.preferable_time,training_service_bookings.status,training_service_bookings.user_id' )
            ->select ( 'user_addresses.address, user_addresses.houseno_floor, user_addresses.building_blockno, user_addresses.landmark_areaname, user_addresses.city, user_addresses.state, user_addresses.country, user_addresses.zip_code, user_addresses.type' )
            ->select ( '(SELECT COUNT(*) FROM quotations WHERE quotations.booking_id = training_service_bookings.id) as total_quote' )

            ->join ( 'services', 'services.id = training_service_bookings.service_id', 'left' )
            ->join ( 'user_addresses', 'user_addresses.id = training_service_bookings.address_id', 'left' )
            ->join ( 'packages', 'packages.id = training_service_bookings.package_id', 'left' )
            ->where ( 'training_service_bookings.user_id', $user_id )
            ->whereIn ( 'training_service_bookings.status', ['New', 'Halted'] )
            ->get ()
            ->getResult ();

        foreach ( $trainingBookings as $booking ) {
            $booking->addons = $this->fetchBookingAddons ( $booking->booking_id );
        }
        return $trainingBookings;
    }

    public function getUserNewBoardingBookings( $user_id )
    {
        // Boarding Bookings
        $boardingBookings = $this->db->table ( 'boarding_service_bookings' )
            ->select ( 'services.name as service_name' )
            ->select ( 'packages.duration_days as days' )
            ->select ( 'boarding_service_bookings.id as booking_id, boarding_service_bookings.service_id as service_id, service_start_date, service_end_date,preferable_time,boarding_service_bookings.status, boarding_service_bookings.user_id' )
            ->select ( 'user_addresses.address, user_addresses.houseno_floor, user_addresses.building_blockno, user_addresses.landmark_areaname, user_addresses.city, user_addresses.state, user_addresses.country, user_addresses.zip_code, user_addresses.type' )
            ->select ( '(SELECT COUNT(*) FROM quotations WHERE quotations.booking_id = boarding_service_bookings.id) as total_quote' )
            ->select ( 'boarding_service_bookings.id as booking_id, boarding_service_bookings.service_id as service_id' )

            ->join ( 'services', 'services.id = boarding_service_bookings.service_id', 'left' )
            ->join ( 'user_addresses', 'user_addresses.id = boarding_service_bookings.address_id', 'left' )
            ->join ( 'packages', 'packages.id = boarding_service_bookings.package_id', 'left' )
            ->where ( 'boarding_service_bookings.user_id', $user_id )
            ->whereIn ( 'boarding_service_bookings.status', ['New', 'Halted'] )
            ->get ()
            ->getResult ();
        foreach ( $boardingBookings as $booking ) {
            $booking->addons = $this->fetchBookingAddons ( $booking->booking_id );
        }
        return $boardingBookings;
    }

    public function getUserNewGroomingBookings( $user_id )
    {
        // Fetch grooming bookings without package data
        $groomingBookings = $this->db->table ( 'grooming_service_bookings' )
            ->select ( 'services.name as service_name, services.id as service_id' )
            ->select ( 'grooming_service_bookings.id as booking_id, grooming_service_bookings.service_id as service_id, service_start_date, preferable_time,grooming_service_bookings.status, grooming_service_bookings.user_id' )
            ->select ( 'user_addresses.address, user_addresses.houseno_floor, user_addresses.building_blockno, user_addresses.landmark_areaname, user_addresses.city, user_addresses.state, user_addresses.country, user_addresses.zip_code, user_addresses.type' )
            ->select ( '(SELECT COUNT(*) FROM quotations WHERE quotations.booking_id = grooming_service_bookings.id) as total_quote' )

            ->join ( 'user_addresses', 'user_addresses.id = grooming_service_bookings.address_id', 'left' )
            ->join ( 'services', 'services.id = grooming_service_bookings.service_id', 'left' )

            ->where ( 'grooming_service_bookings.user_id', $user_id )
            ->whereIn ( 'grooming_service_bookings.status', ['New', 'Halted'] )
            ->get ()
            ->getResult ();

        // Fetch all packages related to the bookings in one query
        $bookingIds = array_column ( $groomingBookings, 'booking_id' );
        $packages   = [];

        if ( !empty ( $bookingIds ) ) {
            $packageResults = $this->db->table ( 'grooming_booking_packages' )
                ->select ( 'grooming_booking_packages.booking_id, packages.id as package_id, packages.package_name as package_name' )
                ->join ( 'packages', 'packages.id = grooming_booking_packages.package_id', 'left' )
                ->whereIn ( 'grooming_booking_packages.booking_id', $bookingIds )
                ->get ()
                ->getResult ();

            // Group packages by booking_id
            foreach ( $packageResults as $package ) {
                $packages[$package->booking_id][] = $package;
            }
        }

        // Merge packages into bookings
        foreach ( $groomingBookings as $booking ) {
            $booking->packages = $packages[$booking->booking_id] ?? [];
            $booking->addons   = $this->fetchBookingAddons ( $booking->booking_id );
        }

        return $groomingBookings;
    }

    public function fetchBookingAddons( $booking_id )
    {
        $addons = $this->db->table ( 'booking_addons' )
            ->select ( 'addon' )
            ->where ( 'booking_addons.booking_id', $booking_id )
            ->get ()
            ->getResult ();

        return array_map ( function ( $addon )
        {
            return [
                "name" => $addon->addon,
            ];
        }, $addons );
    }

    public function getUserBookings( $user_id )
    {
        $walkingBookings = $this->db->table ( 'walking_service_bookings' )
            ->select ( 'walking_service_bookings.id' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )
            ->select ( 'services.name as service_name,services.id as service_id' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price' )
            ->select ( 'walking_service_bookings.id as booking_id,walking_service_bookings.service_start_date,walking_service_bookings.preferable_time,
            walking_service_bookings.service_frequency,walking_service_bookings.walk_duration' )
            ->select ( 'walking_service_bookings.created_at' )
            ->select ( 'walking_tracking.service_time, walking_tracking.status' )
            ->select ( 'quotations.id as quotation_id' )

            ->join ( 'services', 'services.id=walking_service_bookings.service_id', 'left' )
            ->join ( 'packages', 'packages.id=walking_service_bookings.package_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=walking_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )
            ->join ( 'walking_tracking', 'walking_tracking.booking_id=walking_service_bookings.id', 'left' )

            ->where ( 'walking_service_bookings.user_id', $user_id )
            ->where ( 'walking_service_bookings.status', 'Confirmed' )

            ->orderBy ( 'walking_service_bookings.id', "DESC" )
            ->groupBy ( 'walking_service_bookings.id' )

            ->get ()
            ->getResult ();

        $trainingBookings = $this->db->table ( 'training_service_bookings' )
            ->select ( 'training_service_bookings.id' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )

            ->select ( 'services.name as service_name' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price' )
            // ->select('training_service_bookings.id as booking_id,service_start_date,preferable_time,service_frequency,walk_duration,addons')
            ->select ( 'training_service_bookings.created_at' )
            ->select ( 'quotations.id as quotation_id' )

            ->join ( 'services', 'services.id=training_service_bookings.service_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=training_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )
            ->join ( 'packages', 'packages.id=training_service_bookings.package_id', 'left' )

            ->where ( 'training_service_bookings.user_id', $user_id )

            ->get ()
            ->getResult ();

        $boardingBookings = $this->db->table ( 'boarding_service_bookings' )
            ->select ( 'boarding_service_bookings.id' )
            ->select ( 'services.name as service_name' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )
            // ->select('boarding_service_bookings.id as booking_id,service_start_date,preferable_time,service_frequency,walk_duration,addons')
            ->select ( 'boarding_service_bookings.created_at' )
            ->select ( 'quotations.id as quotation_id' )

            ->join ( 'services', 'services.id=boarding_service_bookings.service_id', 'left' )
            ->join ( 'packages', 'packages.id=boarding_service_bookings.package_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=boarding_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'boarding_service_bookings.user_id', $user_id )

            ->get ()
            ->getResult ();

        $groomingBookings = $this->db->table ( 'grooming_service_bookings' )
            ->select ( 'grooming_service_bookings.id' )
            ->select ( 'services.name as service_name' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )
            // ->select('grooming_service_bookings.id as booking_id,service_start_date,preferable_time,service_frequency,walk_duration,addons')
            ->select ( 'grooming_service_bookings.created_at' )
            ->select ( 'quotations.id as quotation_id' )

            ->join ( 'services', 'services.id=grooming_service_bookings.service_id', 'left' )
            ->join ( 'packages', 'packages.id=grooming_service_bookings.package_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=grooming_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'grooming_service_bookings.user_id', $user_id )

            ->get ()
            ->getResult ();

        return array_merge ( $walkingBookings, $trainingBookings, $boardingBookings, $groomingBookings );
    }
    public function getUserConfirmedWalkingBookings( $user_id )
    {
        $builder = $this->db->table ( 'walking_service_bookings wsb' );

        $builder->select ( [
            'wsb.id',
            'sp.name AS provider_name',
            'sp.profile',
            'sp.id AS provider_id',
            's.name AS service_name',
            's.id AS service_id',
            'wsb.id AS booking_id',
            'wsb.service_start_date',
            'wsb.service_end_date',
            'wsb.preferable_time',
            'wsb.is_paused',
            'wsb.created_at',
            'wt.service_time',
            'wt.status',
            'q.sp_timings',
            'spl.resume_date'
        ] );

        $builder

            ->join ( 'quotations q', 'q.booking_id = wsb.id', 'left' )
            ->join ( 'service_providers sp', 'sp.id = q.provider_id', 'left' )
            ->join ( 'walking_tracking wt', 'wt.booking_id = wsb.id', 'left' )
            ->join ( 'service_pause_logs as spl', 'spl.booking_id=wsb.id', 'left' );

        $builder->where ( 'wsb.user_id', $user_id );
        $builder->groupStart ()
            ->groupStart ()
            ->where ( 'wsb.status', 'Confirmed' )
            ->where ( 'q.status', 'Accepted' )
            ->groupEnd ()
            ->orGroupStart ()
            ->where ( 'wsb.status', 'onHold' )
            ->where ( 'wsb.is_paused', 1 )
            ->groupEnd ()
            ->groupEnd ();

        $builder->orderBy ( 'wsb.id', 'DESC' );
        $builder->groupBy ( 'wsb.id' );

        return $builder->get ()->getResult ();
    }
    public function getUserWalkingBookings( $user_id )
    {
        $builder = $this->db->table ( 'walking_service_bookings wsb' );

        $builder->select ( [
            'wsb.id',
            'sp.name AS provider_name',
            'sp.profile',
            'sp.id AS provider_id',
            's.name AS service_name',
            's.id AS service_id',
            'p.package_name',
            'p.price AS package_price',
            'wsb.id AS booking_id',
            'wsb.service_start_date',
            'wsb.service_end_date',
            'wsb.preferable_time',
            'wsb.service_frequency',
            'wsb.walk_duration',
            'wsb.is_paused',
            'wsb.created_at',
            'wt.service_time',
            'wt.status',
            'q.id AS quotation_id',
            'q.sp_timings',
            'spl.resume_date'
        ] );

        $builder
            ->join ( 'services s', 's.id = wsb.service_id', 'left' )
            ->join ( 'packages p', 'p.id = wsb.package_id', 'left' )
            ->join ( 'quotations q', 'q.booking_id = wsb.id', 'left' )
            ->join ( 'service_providers sp', 'sp.id = q.provider_id', 'left' )
            ->join ( 'walking_tracking wt', 'wt.booking_id = wsb.id', 'left' )
            ->join ( 'service_pause_logs as spl', 'spl.booking_id=wsb.id', 'left' );

        $builder->where ( 'wsb.user_id', $user_id );
        $builder->groupStart ()
            ->groupStart ()
            ->where ( 'wsb.status', 'Confirmed' )
            ->where ( 'q.status', 'Accepted' )
            ->groupEnd ()
            ->orGroupStart ()
            ->where ( 'wsb.status', 'onHold' )
            ->where ( 'wsb.is_paused', 1 )
            ->groupEnd ()
            ->groupEnd ();

        $builder->orderBy ( 'wsb.id', 'DESC' );
        $builder->groupBy ( 'wsb.id' );

        return $builder->get ()->getResult ();
    }

    public function getUsertrainingBookings( $user_id )
    {
        return $this->db->table ( 'training_service_bookings' )
            ->select ( 'training_service_bookings.id' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )

            ->select ( 'services.name as service_name,services.id as service_id' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price' )
            ->select ( 'training_service_bookings.id as booking_id,training_service_bookings.service_start_date,training_service_bookings.preferable_time' )
            ->select ( 'training_service_bookings.created_at' )
            ->select ( 'quotations.id as quotation_id,quotations.sp_timings' )

            ->join ( 'services', 'services.id=training_service_bookings.service_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=training_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )
            ->join ( 'packages', 'packages.id=training_service_bookings.package_id', 'left' )

            ->where ( 'training_service_bookings.user_id', $user_id )
            ->where ( 'training_service_bookings.status', 'Confirmed' )
            ->where ( 'quotations.status', 'Accepted' )

            ->orderBy ( 'training_service_bookings.id', "DESC" )

            ->groupBy ( 'training_service_bookings.id' )

            ->get ()
            ->getResult ();
    }
    public function getUserboardingBookings( $user_id )
    {
        return $this->db->table ( 'boarding_service_bookings' )
            ->select ( 'boarding_service_bookings.id' )
            ->select ( 'services.name as service_name' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price,packages.price as price_per' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )
            ->select ( 'boarding_service_bookings.id as booking_id,service_start_date,preferable_time,service_end_date' )
            ->select ( 'boarding_service_bookings.created_at' )
            ->select ( 'quotations.id as quotation_id' )

            ->join ( 'services', 'services.id=boarding_service_bookings.service_id', 'left' )
            ->join ( 'packages', 'packages.id=boarding_service_bookings.package_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=boarding_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'boarding_service_bookings.user_id', $user_id )
            ->where ( 'boarding_service_bookings.status', 'Confirmed' )

            ->orderBy ( 'boarding_service_bookings.id', "DESC" )

            ->groupBy ( 'boarding_service_bookings.id' )

            ->get ()
            ->getResult ();
    }
    public function getUsergroomingBookings( $user_id )
    {
        $groomingBookings = $this->db->table ( 'grooming_service_bookings' )
            ->select ( 'grooming_service_bookings.id' )
            ->select ( 'services.name as service_name,services.id as service_id' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.id as provider_id' )
            ->select ( 'grooming_service_bookings.id as booking_id,service_start_date,preferable_time' )
            ->select ( 'grooming_service_bookings.created_at' )
            ->select ( 'quotations.id as quotation_id,quotations.sp_timings' )

            ->join ( 'services', 'services.id=grooming_service_bookings.service_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=grooming_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'grooming_service_bookings.user_id', $user_id )
            ->where ( 'grooming_service_bookings.status', 'Confirmed' )
            ->where ( 'quotations.status', 'Accepted' )

            ->orderBy ( 'grooming_service_bookings.id', "DESC" )

            ->groupBy ( 'grooming_service_bookings.id' )

            ->get ()
            ->getResult ();

        $bookingIds = array_column ( $groomingBookings, 'booking_id' );
        $packages   = [];

        if ( !empty ( $bookingIds ) ) {
            $packageResults = $this->db->table ( 'grooming_booking_packages' )
                ->select ( 'grooming_booking_packages.booking_id, packages.id as package_id, packages.package_name as package_name,packages.price,packages.included_addons' )
                ->join ( 'packages', 'packages.id = grooming_booking_packages.package_id', 'left' )
                ->whereIn ( 'grooming_booking_packages.booking_id', $bookingIds )
                ->get ()
                ->getResult ();

            foreach ( $packageResults as $package ) {
                $packages[$package->booking_id][] = $package;
                $package->included_addons         = !empty ( $package->included_addons ) ? explode ( ',', (string) $package->included_addons ) : [];
            }
        }
        foreach ( $groomingBookings as $booking ) {
            $booking->packages = $packages[$booking->booking_id] ?? [];
        }
        return $groomingBookings;
    }
    public function getQuotations( $user_id )
    {
        return $quotations = $this->db->table ( 'quotations' )
            ->select ( 'quotations.bid_amount,quotations.total_amount' )
            ->select ( 'services.name as service_name' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile' )

            ->join ( 'services', 'services.id=quotations.service_id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'quotations.status', 'New' )
            ->where ( 'user_id', $user_id )

            ->orderBy ( 'quotations.id', 'DESC' )

            ->groupBy ( 'quotations.service_id' )

            ->get ()
            ->getResult ();
    }

    public function getUserQuotations( $user_id )
    {
        return $this->db->table ( 'quotations' )
            ->select ( 'quotations.bid_amount,quotations.total_amount' )
            ->select ( 'services.name as service_name' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile,service_providers.device_token' )
            ->select ( 'walking_service_bookings.id' )

            ->join ( 'services', 'services.id=quotations.service_id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )
            ->join ( 'walking_service_bookings', 'walking_service_bookings.id=quotations.booking_id', 'left' )

            ->where ( 'quotations.status', 'New' )
            ->where ( 'walking_service_bookings.status', 'New' )
            ->where ( 'walking_service_bookings.user_id', $user_id )
            ->limit ( 10 )

            ->orderBy ( 'quotations.id', 'DESC' )
            // ->groupBy('quotations.service_id')

            ->get ()
            ->getResult ();
    }

    public function getQuotationsByCity( $city )
    {
        return $quotations = $this->db->table ( 'quotations' )
            ->select ( 'quotations.bid_amount,quotations.total_amount' )
            ->select ( 'services.name as service_name' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile' )
            ->join ( 'services', 'services.id=quotations.service_id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'quotations.status', 'New' )
            ->join ( 'users', 'users.id=quotations.user_id', 'left' )
            ->where ( 'users.city', $city )
            ->limit ( 10 )
            ->orderBy ( 'quotations.id', 'DESC' )

            ->get ()
            ->getResult ();
    }
    public function getUserAddress( $user_id, $address_id )
    {
        return $address = $this->db->table ( 'user_addresses' )
            ->select ( 'user_addresses.*' )
            ->select ( 'users.name' )

            ->join ( 'users', 'users.id=user_addresses.user_id', 'left' )

            ->where ( 'user_addresses.user_id', $user_id )
            ->where ( 'user_addresses.id', $address_id )
            ->get ()
            ->getRow ();
    }
    public function getRandomQuotations()
    {
        return $quotations = $this->db->table ( 'quotations' )
            ->select ( 'quotations.bid_amount,quotations.total_amount' )
            ->select ( 'services.name as service_name' )
            ->select ( 'service_providers.name as provider_name,service_providers.profile' )
            ->join ( 'services', 'services.id=quotations.service_id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )

            ->where ( 'quotations.status', 'New' )
            ->limit ( 10 )
            ->orderBy ( 'quotations.id', 'DESC' )
            ->get ()
            ->getResult ();
    }
    public function getNotifications( $userId )
    {
        return $notifications = $this->db->table ( 'notifications' )
            ->select ( 'message,created_at' )
            ->where ( 'user_id', $userId )
            ->where ( 'user_type', 'user' )
            ->orderBy ( 'id', 'DESC' )
            ->get ()
            ->getResult ();
    }
    public function getOffers()
    {
        return $offers = $this->db->table ( 'coupons' )
            ->select ( '*' )
            ->get ()
            ->getResult ();
    }
    public function createRating( $data )
    {
        return $this->db->table ( 'sp_reviews' )->insert ( $data );
    }
    public function checkRating( $booking_id )
    {
        return $this->db->table ( 'sp_reviews' )->where ( ['booking_id' => $booking_id] );
    }
    public function getTracking( $booking_id, $provider_id )
    {
        return $this->db->table ( 'walking_tracking' )

            ->select ( 'walking_tracking.service_time, walking_tracking.status' )
            ->where ( 'booking_id', $booking_id )
            ->where ( 'provider_id', $provider_id )
            ->where ( 'provider_id', $provider_id )

            ->get ()->getResult ();
    }
    public function getExtendRequests( $user_id )
    {
        $walkingBookings = $this->db->table ( 'walking_service_bookings' )
            ->select ( 'services.name as service_name,services.id as service_id' )
            ->select ( 'walking_service_bookings.type as booking_type,walking_service_bookings.original_booking_id,' )
            ->select ( 'services.name as service_name,services.id as service_id' )
            ->select ( 'packages.package_name as package_name ,packages.price as package_price,packages.duration_days as days' )
            ->select ( 'walking_service_bookings.id as booking_id,walking_service_bookings.service_start_date,walking_service_bookings.preferable_time,walking_service_bookings.service_frequency,walking_service_bookings.walk_duration,walking_service_bookings.approval as approval' )
            ->select ( 'walking_service_bookings.created_at' )
            ->select ( 'user_addresses.address, user_addresses.houseno_floor, user_addresses.building_blockno, user_addresses.landmark_areaname, user_addresses.city, user_addresses.state, user_addresses.country, user_addresses.zip_code, user_addresses.type' )

            ->join ( 'services', 'services.id=walking_service_bookings.service_id', 'left' )
            ->join ( 'packages', 'packages.id=walking_service_bookings.package_id', 'left' )
            ->join ( 'quotations', 'quotations.booking_id=walking_service_bookings.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id=quotations.provider_id', 'left' )
            ->join ( 'walking_tracking', 'walking_tracking.booking_id=walking_service_bookings.id', 'left' )
            ->join ( 'user_addresses', 'user_addresses.id = walking_service_bookings.address_id', 'left' )

            ->where ( 'walking_service_bookings.user_id', $user_id )
            ->where ( 'walking_service_bookings.status', 'onHold' )
            ->where ( 'walking_service_bookings.type', 'extend' )

            ->groupStart ()
            ->where ( 'walking_service_bookings.payment_status', 'pending' )
            ->orWhere ( 'walking_service_bookings.payment_status IS NULL' )
            ->groupEnd ()

            ->orderBy ( 'walking_service_bookings.id', "DESC" )
            ->groupBy ( 'walking_service_bookings.id' )

            ->get ()
            ->getResult ();

        foreach ( $walkingBookings as $booking ) {
            $addons = $this->db->table ( 'booking_addons' )
                ->select ( 'addon' )
                ->where ( 'booking_addons.booking_id', $booking->booking_id )
                ->get ()
                ->getResult ();

            $booking->addons = array_map ( function ( $addon )
            {
                return [
                    "name" => $addon->addon,
                ];
            }, $addons );
        }
        return $walkingBookings;
    }
    public function getPetDetails( $booking_id )
    {
        return $this->db->table ( 'booking_pets' )
            ->select ( 'user_pets.name,user_pets.image' )
            ->join ( 'user_pets', 'user_pets.id = booking_pets.pet_id', 'left' )
            ->where ( 'booking_pets.booking_id', $booking_id )
            ->get ()->getResult ();
    }

    public function getUserPets( $user_id )
    {
        return $this->db->table ( 'user_pets' )
            ->select ( 'id' )
            ->where ( 'user_id', $user_id )
            ->get ()
            ->getResult ();
    }
    public function getUserAddresses( $user_id )
    {
        return $this->db->table ( 'user_addresses' )
            ->select ( 'id' )
            ->where ( 'user_id', $user_id )
            ->get ()
            ->getResult ();
    }
    public function getUserRefundAmount( $user_id )
    {
        return $this->db->table ( 'user_withdrawal_wallet' )
            ->selectSum ( 'amount' )
            ->where ( 'user_id', $user_id )
            ->get ()
            ->getRow ();
    }
    public function getBreeds()
    {
        return $this->db->table ( 'dog_breeds' )
            ->select ( 'label,value' )
            ->orderBy ( 'label', 'ASC' )
            ->get ()
            ->getResult ();
    }

    public function checkAddressBooking( $user_id, $id )
    {
        return $this->db->table ( 'walking_service_bookings' )
            ->select ( 'id' )
            ->where ( 'user_id', $user_id )
            ->where ( 'address_id', $id )
            ->whereIn ( 'status', ['New', 'Confirmed', 'Cancelled', 'Completed', 'onHold'] )
            ->get ()
            ->getResult ();
    }
    public function getCompletedWalks( $user_id )
    {
        return $this->db->table ( 'walking_service_bookings' )
            ->select ( 'walking_tracking.booking_id, walking_tracking.provider_id, walking_tracking.service_time, walking_tracking.end_time as completed_at, walking_tracking.is_approved, walking_tracking.payment_status' )
            ->select ( 'user_pets.id as pet_id, user_pets.name as pet_name' )
            ->join ( 'walking_tracking', 'walking_service_bookings.id = walking_tracking.booking_id', 'inner' )
            ->join ( 'user_pets', 'user_pets.id = walking_tracking.pet_id', 'left' )
            ->where ( 'walking_service_bookings.user_id', $user_id )
            ->where ( 'walking_tracking.status', 'completed' )
            ->groupStart ()
            // ->where('walking_tracking.is_approved', 'false')
            ->orWhere ( 'walking_tracking.is_approved IS NULL' )
            ->groupEnd ()
            ->where ( 'walking_service_bookings.status', 'Confirmed' )
            ->where ( 'walking_tracking.tracking_date', gmdate ( 'Y-m-d' ) )
            ->get ()
            ->getResult ();
    }
    public function createQuery( $data )
    {
        return $this->db->table ( 'queries' )
            ->insert ( $data );
    }

    public function createUser( $data )
    {
        $this->db->table ( 'users' )->insert ( $data );
        return $user_id = $this->db->insertID ();
    }
    public function checkUser( $phone )
    {
        return $this->db->table ( 'users' )->where ( 'phone', $phone )->get ()->getRow ();
    }
    public function getAddressByAddressId( $id )
    {
        return $this->db->table ( $this->address )
            ->where ( 'id', $id )
            ->get ()
            ->getRow ();
    }
    public function getPreviousProviders( $user_id, $service_id )
    {
        $tableMapping = [
            '1' => 'boarding_service_bookings',
            '2' => 'grooming_service_bookings',
            '3' => 'training_service_bookings',
            '4' => 'walking_service_bookings',
        ];
        $table        = $tableMapping[$service_id] ?? 'walking_service_bookings';
        return $this->db->table ( $table )
            ->select ( 'service_providers.id as provider_id, service_providers.name as provider_name' )

            ->join ( 'quotations', 'quotations.booking_id = ' . $table . '.id', 'left' )
            ->join ( 'service_providers', 'service_providers.id = quotations.provider_id', 'left' )

            ->where ( $table . '.user_id', $user_id )
            ->where ( $table . '.service_id', $service_id )
            ->whereIn ( $table . '.status', ['Completed', 'Confirmed'] )
            ->whereIn ( 'quotations.status', ['Accepted', 'Completed'] )
            ->groupBy ( 'provider_id' )
            ->get ()
            ->getResult ();
    }
}
