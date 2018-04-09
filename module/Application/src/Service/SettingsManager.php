<?php
namespace Application\Service;

use User\Entity\User;
use Zend\Math\Rand;

/**
 * This service is responsible for registering the user.
 */
class SettingsManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  

    /**
     * Constructs the service.
     */
    public function __construct($entityManager) 
    {
        $this->entityManager = $entityManager;
    }
    
     /**
     * Generates an email reset token for the user. This token is then stored in database and 
     * sent to the user's E-mail address. When the user clicks the link in E-mail message, they are 
     * directed to the confirm email page.
     */
    public function generateEmailResetToken($user, $newEmail)
    {
        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
        $user->setEmailResetToken($token);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setEmailResetTokenCreationDate($currentDate);  
        
        $user->setEmailResetEmail($newEmail);
        
        $this->entityManager->flush();
        
        $subject = 'Reset Email';
            
        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $emailResetUrl = 'https://' . $httpHost 
                . '/settings/confirm-email?token=' . $token;
        
        $body = "Please follow the link below to reset your password:\n";
        $body .= "$emailResetUrl\n";
        $body .= "If you haven't asked to reset your email, please ignore this message.\n";
        
        $header = 'From: Policy Bubble';
        
        // Send email to user.
        mail($newEmail, $subject, $body, $header);
    }
    
    /**
     * Checks whether the given email reset token is a valid one.
     */
    public function validateEmailResetToken($emailResetToken)
    {
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmailResetToken($emailResetToken);
        
        if($user==null) {
            return false;
        }
        
        $tokenCreationDate = $user->getEmailResetTokenCreationDate();
        $tokenCreationDate = strtotime($tokenCreationDate);
        
        $currentDate = strtotime('now');
        
        if ($currentDate - $tokenCreationDate > 24*60*60) {
            return false; // expired
        }
        
        return true;
    }
    
    /**
     * Confirms the user's email reset token.
     */
    public function confirmEmail($emailResetToken)
    {
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByEmailResetToken($emailResetToken);
        
        if($user==null) {
            return false;
        }
        
        $newEmail = $user->getEmailResetEmail();
        
        $user->setEmail($newEmail);
        
        // Apply changes
        $this->entityManager->flush();

        return true;
        
    }
    

}

