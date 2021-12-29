<?php
/*
    PHP OAuth using Curl
*/

  class github {

    private $app = '';
    private $client_id     = '';
    private $client_secret = '';

    private $access_token  = '';
    private $access_code   = '';

    private $auth_url      = 'https://github.com/login/oauth/authorize';
    private $token_url     = 'https://github.com/login/oauth/access_token';
    private $user_api_url  = 'https://api.github.com/user';

    public $user_info = null;
    public $err       = null;

    public function __construct($app, $client_id, $client_secret) {
        session_start();
        $this->app           = $app;
        $this->client_id     = $client_id;
        $this->client_secret = $client_secret;
        $this->access_code   = $_GET['code'];
        $this->access_token  = $_SESSION['access_token'];
    }

    private function getToken() {
      $ch = curl_init();
      $postParams = [
       'client_id'      => $this->client_id ,
       'client_secret'  => $this->client_secret,
       'code'           => $this->access_code
                    ];
      curl_setopt($ch, CURLOPT_URL, $this->token_url);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$postParams);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_HTTPHEADER,array('Accept: application/json'));

      $response = curl_exec($ch);
      curl_close($ch);

      $resp = json_decode($response);

      //if ($resp->error) {}
      if ($resp->error_description) { $this->err = $resp->error_description; }
      //if ($resp->error_uri) {}

      if ($resp->access_token) {
        $_SESSION['access_token'] = $resp->access_token ;
        $this->access_token = $_SESSION['access_token'];
      }
    }

    private function get_user() {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->user_api_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
      curl_setopt($ch, CURLOPT_HTTPHEADER,
         array( 'Accept: application/json', 
                'Authorization: token '.$this->access_token,
                'User-Agent: '.$this->app ));

      $response = curl_exec($ch);
      curl_close($ch);

      return(json_decode($response,true));
    }

    public function getUser() {
      if ($this->access_token == '') {
        if ($this->access_code == '') {
          return null;
        } else {
          $this->getToken();
        }
      }
      if ($this->access_token != '') {
        $this->user_info = $this->get_user();
        return ($this->user_info);
      } else {
        return null;
      }
    }

    public function authenticate() {
      header('Location: '.$this->auth_url.'?client_id='.$this->client_id.'&scope=read:user');
      exit;
    }
  }

// Configure these !!!
  $app            = '';
  $client_id      = '';
  $client_secret  = ''; 
//

  $gh = new github($app, $client_id, $client_secret);

  $user_info = $gh->getUser();

  if ($user_info == null) {
    $gh->authenticate();
  } else {

    echo "<p><b>Howdy</b> ".$user_info['name']."</p>";

    foreach ($user_info as $key => $value) {
      echo $key ." = ". $value ."</br>";
    }
  }

?>