<?php
namespace App;

/**
 * This class Sends Mails using php mail function.
 * For some reason phpMailer does not love me. 
 * I am too lazy to try and find out why it is not working
 *
 * @author James
 */
class Mailer {    
    private $to;
    private $fromName;
    private $fromEmail;
    private $subject;
    private $message;
    
    public function setFrom($email, $name = 'Egerton Sacco') {
        $this->fromName = $name;
        $this->fromEmail = $email;
    }
    
    public function setTo($email) {
        $this->to = $email;
    }
    
    public function setSubject($subject) {
        $this->subject = $subject;
    }
    
    public function setMessage($message) {
        $this->message = $message;
    }
    
    public function send() {
        
        $headers = array(
            'MIME-Version: 1.0',
            'Content-type:text/html; charset=iso-8859-1',
            'Content-Transfer-Encoding: 7bit'
        );
        
        if(!is_null($this->fromEmail) || !is_null($this->fromName)) {
            $from = sprintf("From: %s<%s>", trim($this->fromName), trim($this->fromEmail));
            array_push($headers, $from);
        }
        return mail($this->to, $this->subject, $this->message, implode('\r\n', $headers));
    }
}
