<?php
namespace Application\Service;

/**
 * This service class is used to deliver an E-mail message to recipient.
 */
class MailSender 
{
    /**
     * Sends the mail message.
     */
    public function sendMail($sender, $recipient, $subject, $text) 
    {
        $result = false;
        try {        
            
            $subject = "Contact Us: " . $subject;
            mail($recipient, $subject, $text, null, "-f$sender");
            
            // Send E-mail message
            $result = true;
            
        } catch(\Exception $e) {
            $result = false;
        }
        
        return $result;
    }
};


