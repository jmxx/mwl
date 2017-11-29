<?php namespace MWL\Auth;

use \Illuminate\Support\Carbon;

class EmailTokenValidation {

  private $password = null;

  private $algorithm = null;

  private $iv = null;

  public function __construct() {

    $this->algorithm = 'DES-EDE3-CBC';

    $this->password = 'zxasdWER2REGfgrty4564&^2';

    $this->iv = '12345678';

  }

  public function generateToken($email, $expirationDays = 7) {
    $data = [
      'email' => $email,
      'exp'=> Carbon::now()->addDays($expirationDays)->timestamp,
      'sub' => 'email_validation',
    ];

    return $this->encrypt($data);
  }

  public function validateToken($key) {
    $data = $this->decrypt($key);

    // $expirationDate = $data->exp;
    //
    // $email = $data->email;

    return $data;
  }

  private function encrypt(array $data) {
    $payload = json_encode($data);

    $key = openssl_encrypt($payload, $this->algorithm, $this->password, 0, $this->iv);

    return str_replace('%', '.', urlencode($key));
  }

  private function decrypt($key) {
    $key = str_replace('.', '%', urlencode($key));

    $data = openssl_decrypt(urldecode($key), $this->algorithm, $this->password, 0, $this->iv);

    return json_decode($data);
  }

}
