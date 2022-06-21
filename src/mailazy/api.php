<?php

class API {

    public $apiurl;
    public $apikey;
    public $apisecret;
    
    public $to = [];
    public $cc = [];
    public $bcc = [];
    public $subject = "";
    public $body = "";
    public $from = "";
    public $replyTo = "";
    public $attachments = [];

    public function __construct() {
        $this->setApiurl("https://api.mailazy.com/");
        $this->isHTML(true);
    }

    /**
     * Set API URL
     * 
     * @param type $url
     * @return type
     */
    public function setApiurl($url) {
        return $this->apiurl = $url;
    }

    /**
     * Set API key
     * 
     * @param type $apikey
     * @return type
     */
    public function setApikey($apikey) {
        return $this->apikey = $apikey;
    }

    /**
     * Set API secret
     * 
     * @param type $apisecret
     * @return type
     */
    public function setApisecret($apisecret) {
        return $this->apisecret = $apisecret;
    }

    /**
     * Set To Address
     * 
     * @param type $email
     * @param type $name
     * @return type
     */
    public function addAddress($email, $name = "") {
        $this->to = isset($this->to) ? $this->to : array();
        if (empty($name)) {
            $toAddress = array($email);
        } else {
            $toAddress = array($name . '<' . $email . '>');
        }
        return $this->to = array_merge($this->to, $toAddress);
    }

    /**
     * Set CC Address
     * 
     * @param type $email
     * @param type $name
     * @return type
     */
    public function addCC($email, $name = "") {
        $this->cc = isset($this->cc) ? $this->cc : array();
        if (empty($name)) {
            $toAddress = array($email);
        } else {
            $toAddress = array($name . '<' . $email . '>');
        }
        return $this->cc = array_merge($this->cc, $toAddress);
    }

    /**
     * Set BCC Address
     * 
     * @param type $email
     * @param type $name
     * @return type
     */
    public function addBCC($email, $name = "") {
        $this->bcc = isset($this->bcc) ? $this->bcc : array();
        if (empty($name)) {
            $toAddress = array($email);
        } else {
            $toAddress = array($name . '<' . $email . '>');
        }
        return $this->bcc = array_merge($this->bcc, $toAddress);
    }

    /**
     * Set subject
     * 
     * @param type $subject
     * @return type
     */
    public function setSubject($subject) {
        return $this->subject = $subject;
    }

    /**
     * Set email message body
     * 
     * @param type $body
     * @return type
     */
    public function setBody($body) {
        return $this->body = $body;
    }

    /**
     * Set from
     * 
     * @param type $from
     * @param type $name
     * @return type
     */
    public function setFrom($from, $name = "") {
        if (empty($name)) {
            $fromAddress = $from;
        } else {
            $fromAddress = $name . '<' . $from . '>';
        }
        return $this->from = $fromAddress;
    }

    /**
     * Set replyTo
     * 
     * @param type $replyTo
     * @param type $name
     * @return type
     */
    public function addReplyTo($replyTo, $name = "") {
        if (empty($name)) {
            $replyToAddress = $replyTo;
        } else {
            $replyToAddress = $name . '<' . $replyTo . '>';
        }
        return $this->replyTo = $replyToAddress;
    }

    /**
     * Set from
     * 
     * @param type $ishtml
     * @return type
     */
    public function isHTML($ishtml) {
        return $this->ishtml = !$ishtml ? false : true;
    }

    /**
     * add Attachment
     * 
     * @param type $file
     * @param type $name
     * @param type $encoding
     * @param type $type
     * @return type
     */
    public function addAttachment($file, $name = '', $encoding = 'base64', $type = 'application/pdf') {
        $this->attachments = isset($this->attachments) ? $this->attachments : array();
        $fileName = (!empty($name) ? $name : basename($file));
        $ContentType = mime_content_type($file) ? mime_content_type($file) : $type;
        $data = file_get_contents($file);
        $attachment = array(array("type" => $ContentType,
                "file_name" => $fileName,
                "content" => ($encoding == 'base64') ? base64_encode($data) : $data));
        return $this->attachments = array_merge($this->attachments, $attachment);
    }

    /**
     * Send Link on Email
     * 
     * @return type
     */
    public function send() {
        $payload = array(
            "to" => $this->to,
            "from" => $this->from,
            "subject" => $this->subject,
            "content" => array(
                array(
                    "type" => "text/plain",
                    "value" => strip_tags($this->body)
                )
            )
        );
        if ($this->ishtml) {
            $payload['content'][] = array(
                "type" => "text/html",
                "value" => $this->body
            );
        }
        if (!empty($this->bcc)) {
            $payload['bcc'] = $this->bcc;
        }
        if (!empty($this->cc)) {
            $payload['cc'] = $this->cc;
        }
        if (!empty($this->replyTo)) {
            $payload['reply_to'] = $this->replyTo;
        }
        if (!empty($this->attachments)) {
            $payload['attachments'] = $this->attachments;
        }
        return $this->request("v1/mail/send", array(
                    "method" => "POST",
                    "headers" => array("X-Api-Key" => $this->apikey,
                        "X-Api-Secret" => $this->apisecret,
                        'Content-Type' => 'application/json'),
                    "body" => json_encode($payload)
        ));
    }

    /**
     * Request from cURL and FSOCKOPEN
     * 
     * @param type $endPointPath
     * @param type $args
     * @return int
     */
    public function request($endPointPath, $args = array()) {
        if (in_array('curl', get_loaded_extensions())) {
            $response = $this->curlRequest($endPointPath, $args);
        } elseif (ini_get('allow_url_fopen')) {
            $response = $this->fsockopenRequest($endPointPath, $args);
        } else {
            $response = array("status_code" => 500, "message" => 'cURL or FSOCKOPEN is not enabled, enable cURL or FSOCKOPEN to get response from mojoAuth API.');
        }
        return $response;
    }

    /**
     * Request from FSOCKOPEN
     * 
     * @param type $endPointPath
     * @param type $options
     * @return type
     */
    private function fsockopenApiMethod($endPointPath, $options) {
        $method = isset($options['method']) ? strtoupper($options['method']) : 'GET';
        $data = isset($options['body']) ? $options['body'] : array();

        $optionsArray = array('http' =>
            array(
                'method' => strtoupper($method),
                'timeout' => 50,
                'ignore_errors' => true
            ),
            "ssl" => array(
                "verify_peer" => false
            )
        );
        if (!empty($data) || $data === true) {
            $optionsArray['http']['content'] = $data;
        }

        foreach ($options['headers'] as $k => $val) {
            $optionsArray['http']['header'] .= "\r\n" . $k . ":" . $val;
        }

        $context = stream_context_create($optionsArray);
        $jsonResponse['response'] = file_get_contents($this->apiurl . $endPointPath, false, $context);
        $parseHeaders = Functions::parseHeaders($http_response_header);
        if (isset($parseHeaders['Content-Encoding']) && $parseHeaders['Content-Encoding'] == 'gzip') {
            $jsonResponse['response'] = gzdecode($jsonResponse['response']);
        }
        $jsonResponse['status_code'] = $parseHeaders['reponse_code'];

        return $jsonResponse;
    }

    /**
     * Request from cURL
     *
     * @param type $endPointPath
     * @param type $options
     * @return type
     */
    private function curlRequest($endPointPath, $options) {
        $method = isset($options['method']) ? strtoupper($options['method']) : 'GET';
        $data = isset($options['body']) ? $options['body'] : array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiurl . $endPointPath);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");
        $headerArray = array();
        foreach ($options['headers'] as $k => $val) {
            $headerArray[] = $k . ":" . $val;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

        if (in_array($method, array('POST'))) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            }
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = array();
        $output['response'] = curl_exec($ch);
        $output['status_code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_error($ch)) {
            $output['response'] = curl_error($ch);
        }
        curl_close($ch);

        return $output;
    }

}
