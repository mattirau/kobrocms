<?php

/**
 * This be a simple mailer class who be sending email!
 * 
 * @author Rajanigandha Balasubramanium
 *   
 */
class Mailer {

   /**
    * Mail from
    * 
    * @var string
    */
   private $from, $to, $subject, $message, $mailer;

   public function __construct($from, $to, $subject, $message) {

      $transport = Swift_SmtpTransport::newInstance('localhost', 25);
      $this->mailer = $mailer = Swift_Mailer::newInstance($transport);

      // We construct mail from params.
      $this->from = $from;
      $this->to = $to;
      $this->subject = $subject;
      $this->message = $message;
   }

   /**
    * Sending dem concrete mails
    * 
    * @return bool Sendings on true, failings on falsE
    */
   public function send() {
      $message = Swift_Message::newInstance($this->subject)
              ->setFrom(array($this->from))
              ->setTo(array($this->to))
              ->setBody($this->message);

      return $this->mailer->send($message);
   }

}
