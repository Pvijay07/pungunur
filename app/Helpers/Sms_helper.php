<?php

namespace App\Libraries;

class Sms_helper
{
  protected $email;
  protected $bulkSmsUrl;
  protected $bulkSmsUser;
  protected $bulkSmsPassword;
  protected $from;
  protected $domain;

  public function __construct()
  {
    $this->email           = \Config\Services::email ();
    $this->bulkSmsUrl      = getenv ( 'SMS_URL' );
    $this->bulkSmsPassword = getenv ( 'SMS_USER' );
    $this->bulkSmsUser     = getenv ( 'SMS_PASS' );

  }

  public static function sendSms( $phone, $body )
  {
    $curl = \Config\Services::curlrequest ();

    try {
      $message    = "%27Dear%20Customer,Your%20OTP%20number%20is%20$body,Regards%20PETSFOLIO%27";
      $url        = $this->buildSmsUrl ( $phone, $message );
      $response   = $curl->request ( 'GET', $url );
      $statusCode = $response->getStatusCode ();
      if ( $statusCode == 200 ) {
        return 200;
      }
      else {
        return $statusCode;
      }
    } catch (\Exception $e) {
      return 500;
    }
  }
  private function buildSmsUrl( $phone, $message )
  {
    return $this->bulkSmsUrl .
      'user=' . $this->bulkSmsUser .
      '&password=' . $this->bulkSmsPassword .
      '&mobile=' . $phone .
      '&message=' . $message .
      '&sender=PFSIND&type=3&template_id=1207168240213327862';
  }


}
