<?php

/**
 * extends PHPMailer as SMTP library to send email via Mailazy SMTP Server
 */
class SMTP extends PHPMailer\PHPMailer\PHPMailer {

    /**
     * default set Mailazy SMTP options
     */
    public function __construct() {
        $this->Host = 'smtp.mailazy.com';
        $this->Port = 587;
        $this->SMTPAuth = true;
        $this->isSMTP();
    }

    /**
     * Allow to set SMTP Authentication username
     * 
     * @param type $username
     */
    function setApikey($username) {
        $this->Username = $username;
    }

    /**
     * Allow to set SMTP Authentication password
     * 
     * @param type $password
     */
    function setApisecret($password) {
        $this->Password = $password;
    }

    /**
     * Allow to set SMTP Email subject
     * 
     * @param type $subject
     */
    function setSubject($subject) {
        $this->Subject = $subject;
    }

    /**
     * Allow to set SMTP Email Body
     * 
     * @param type $string
     */
    function setBody($string) {
        $this->Body = $this->AltBody = $string;
    }

}
