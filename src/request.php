<?php

/**
 * Mailazy PHP library allow to send email through Email API server and SMTP server
 */
class Mailazy {

    public $request = '';

    /**
     * allow to select service type
     * 
     * @param type $serviceType
     * @return type
     */
    public function setRequestType($serviceType) {
        if ($serviceType == 'smtp') {
            $this->isSMTPEmail();
        } else {
            $this->isAPIEmail();
        }
        return $this->request;
    }

    /**
     * Set Mailazy Email Service through SMTP Server
     */
    public function isSMTPEmail() {
        require_once __DIR__ . '/mailazy/smtp.php';
        $this->request = new SMTP();
    }

    /**
     * Set Mailazy Email Service through Email API Server
     */
    public function isAPIEmail() {
        require_once __DIR__ . '/mailazy/api.php';
        $this->request = new API();
    }

}
