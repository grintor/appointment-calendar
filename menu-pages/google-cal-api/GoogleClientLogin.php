<?php

class LoginFailedException extends Exception {}

class GoogleClientLogin {
    public $url;
    public $accountType;
    public $email;
    public $password;
    public $service;
    public $source;
//public $CURLOPT_RETURNTRANSFER
    public static $CALENDAR_SERVICE = 'cl';
    public static $SPREADSHEET_SERVICE = 'wise';

    private $responseCode;
    private $auth;

    public function __construct($email, $password, $service, $source, $accountType = 'GOOGLE',
                                    $url = 'https://www.google.com/accounts/ClientLogin', $autoLogin = true) {
        $this->url = $url;
        $this->accountType = $accountType;
        $this->email = $email;
        $this->password = $password;
        $this->service = $service;
        $this->source = $source;

        if($autoLogin) {
            $this->login();
        }
    }

    public function login() {

        $postData = array(
            'accountType'=>$this->accountType,
            'Email'=>$this->email,
            'Passwd'=>$this->password,
            'service'=>$this->service,
            'source'=>$this->source
        );

        $httprequest = curl_init($this->url);

        curl_setopt($httprequest, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($httprequest, CURLOPT_POST, true);
        curl_setopt($httprequest, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($httprequest, CURLOPT_HEADER, true);
        curl_setopt($httprequest, CURLOPT_POSTFIELDS, $postData);
		//curl_setopt($httprequest, CURLOPT_RETURNTRANSFER)

        $rawresult = curl_exec($httprequest);
        $result = explode("\n", $rawresult);

        foreach($result as $line) {
            $matches = array();
            if( preg_match('/^HTTP.*?\s([0-9]{3})/', $line, $matches) > 0) {
                $this->responseCode = $matches[1];
                if($this->responseCode != '200') {
                    throw new LoginFailedException('The login attempt failed with a response code of ' . $this->responseCode);
                }
            }

            $matches = array();
            if( preg_match('/^Auth=(.*?)$/', $line, $matches) > 0) {
                $this->auth = $matches[1];
            }
        }
    }

    public function getAuth() {
        return $this->auth;
    }

    public function getResponseCode() {
        return $this->responseCode;
    }
}