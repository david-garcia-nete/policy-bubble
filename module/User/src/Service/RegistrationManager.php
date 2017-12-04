<?php
namespace User\Service;

use User\Entity\User;
use User\Entity\Role;
use Zend\Crypt\Password\Bcrypt;
use Zend\Math\Rand;

/**
 * This service is responsible for registering the user.
 */
class RegistrationManager
{
    /**
     * Doctrine entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;  
    
    /**
     * User manager.
     * @var User\Service\UserManager
     */
    private $userManager;

    /**
     * Constructs the service.
     */
    public function __construct($entityManager, $userManager) 
    {
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;

    }
    
    /**
     * This method registers a new user.
     */
    public function registerUser($data) 
    {
        // Do not allow several users with the same email address.
        if($this->userManager->checkUserExists($data['email'])) {
            throw new \Exception("User with email address " . $data['$email'] . " already exists");
        }
        
        // Create new User entity.
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFullName($data['full_name']);        

        // Encrypt password and store the password in encrypted state.
        $bcrypt = new Bcrypt();
        $passwordHash = $bcrypt->create($data['password']);        
        $user->setPassword($passwordHash);
        
        $user->setStatus(2);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setDateCreated($currentDate);        
        
        $roleList = [];
        $roleList['Guest'] = 2;
        
        // Assign roles to user.
        $this->userManager->assignRoles($user, $roleList);        
        
        // Add the entity to the entity manager.
        $this->entityManager->persist($user);
                       
        // Apply changes to database.
        $this->entityManager->flush();
        
        return $user;
    }

    /**
     * Generates a registration confirmation token for the user. This token is then stored in database and 
     * sent to the user's E-mail address. When the user clicks the link in E-mail message, he is 
     * directed to the registration success page.
     */
    public function generateRegistrationConfiramtionToken($user)
    {
        // Generate a token.
        $token = Rand::getString(32, '0123456789abcdefghijklmnopqrstuvwxyz', true);
        $user->setRegistrationConfirmationToken($token);
        
        $currentDate = date('Y-m-d H:i:s');
        $user->setRegistrationConfirmationTokenCreationDate($currentDate);  
        
        $this->entityManager->flush();
        
        $subject = 'Registration';
            
        $httpHost = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'localhost';
        $registrationConfirmationUrl = 'http://' . $httpHost . '/registration/confirm-registration?token=' . $token;
        
        $body = "Please follow the link below to confirm your registration:\n";
        $body .= "$registrationConfirmationUrl\n";
        $body .= "If you haven't asked to register your email, please ignore this message.\n";
        
        // Send email to user.
        mail($user->getEmail(), $subject, $body);
    }
    

    /**
     * Confirms the user's registration token.
     */
    public function confirmRegistration($registrationConfirmationToken)
    {
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByRegistrationConfirmationToken($registrationConfirmationToken);
        
        if($user==null) {
            return false;
        }
        
        $user->setStatus(1);
        
        // Apply changes
        $this->entityManager->flush();

        return true;
        
    }
    
    /**
     * Checks whether the given registration confirmation token is a valid one.
     */
    public function validateRegistrationConfirmationToken($registrationConfirmationToken)
    {
        $user = $this->entityManager->getRepository(User::class)
                ->findOneByRegistrationConfirmationToken($registrationConfirmationToken);
        
        if($user==null) {
            return false;
        }
        
        $tokenCreationDate = $user->getRegistrationConfirmationTokenCreationDate();
        $timeTokenCreationDate = strtotime($tokenCreationDate);
        
        $currentDate = strtotime('now');
        
        if ($currentDate - $timeTokenCreationDate > 24*60*60) {
            return false; // expired
        }
        
        return true;
    }

}

