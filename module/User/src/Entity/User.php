<?php
namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User 
{
    // User status constants.
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.
    
    // User membership constants.
    const MEMBERSHIP_FREE       = 1;    // Free user.
    const MEMBERSHIP_BRONZE     = 2;    // Bronze user.
    const MEMBERSHIP_SILVER     = 3;    // Silver user.
    const MEMBERSHIP_GOLD       = 4;    // Gold user.
    const MEMBERSHIP_PLATINUM   = 5;    // Platinum user.
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="email")  
     */
    protected $email;
    
    /** 
     * @ORM\Column(name="full_name")  
     */
    protected $fullName;

    /** 
     * @ORM\Column(name="password")  
     */
    protected $password;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
        
    /**
     * @ORM\Column(name="pwd_reset_token")  
     */
    protected $passwordResetToken;
    
    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")  
     */
    protected $passwordResetTokenCreationDate;
    
    /**
     * @ORM\Column(name="reg_conf_token")  
     */
    protected $registrationConfirmationToken;
    
    /**
     * @ORM\Column(name="reg_conf_token_creation_date")  
     */
    protected $registrationConfirmationTokenCreationDate;
    
    /**
     * @ORM\ManyToMany(targetEntity="User\Entity\Role")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    private $roles;
    
     /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\Post", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $posts;
    
    /**
     * @ORM\OneToMany(targetEntity="\Application\Entity\TransactionsPayPal", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $transactions;
    
    /** 
     * @ORM\Column(name="membership")  
     */
    protected $membership;
    
    /**
     * @ORM\OneToOne(targetEntity="\Application\Entity\Demographic", mappedBy="user")
     * @ORM\JoinColumn(name="id", referencedColumnName="user_id")
     */
    protected $demographic;
    
    /**
     * Constructor.
     */
    public function __construct() 
    {
        $this->roles = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->transactions = new ArrayCollection();
    }
    
    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets user ID. 
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Returns email.     
     * @return string
     */
    public function getEmail() 
    {
        return $this->email;
    }

    /**
     * Sets email.     
     * @param string $email
     */
    public function setEmail($email) 
    {
        $this->email = $email;
    }
    
    /**
     * Returns full name.
     * @return string     
     */
    public function getFullName() 
    {
        return $this->fullName;
    }       

    /**
     * Sets full name.
     * @param string $fullName
     */
    public function setFullName($fullName) 
    {
        $this->fullName = $fullName;
    }
    
    /**
     * Returns status.
     * @return int     
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList() 
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }    
    
    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];
        
        return 'Unknown';
    }    
    
    /**
     * Sets status.
     * @param int $status     
     */
    public function setStatus($status) 
    {
        $this->status = $status;
    }   
    
    /**
     * Returns password.
     * @return string
     */
    public function getPassword() 
    {
       return $this->password; 
    }
    
    /**
     * Sets password.     
     * @param string $password
     */
    public function setPassword($password) 
    {
        $this->password = $password;
    }
    
    /**
     * Returns the date of user creation.
     * @return string     
     */
    public function getDateCreated() 
    {
        return $this->dateCreated;
    }
    
    /**
     * Sets the date when this user was created.
     * @param string $dateCreated     
     */
    public function setDateCreated($dateCreated) 
    {
        $this->dateCreated = $dateCreated;
    }    
    
    /**
     * Returns password reset token.
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->passwordResetToken;
    }
    
    /**
     * Sets password reset token.
     * @param string $token
     */
    public function setPasswordResetToken($token) 
    {
        $this->passwordResetToken = $token;
    }
    
    /**
     * Returns password reset token's creation date.
     * @return string
     */
    public function getPasswordResetTokenCreationDate()
    {
        return $this->passwordResetTokenCreationDate;
    }
    
    /**
     * Sets password reset token's creation date.
     * @param string $date
     */
    public function setPasswordResetTokenCreationDate($date) 
    {
        $this->passwordResetTokenCreationDate = $date;
    }
    
     /**
     * Returns registration confirmation token.
     * @return string
     */
    public function getRegistrationConfirmationToken()
    {
        return $this->registrationConfirmationToken;
    }
    
    /**
     * Sets registration confirmation token.
     * @param string $token
     */
    public function setRegistrationConfirmationToken($token) 
    {
        $this->registrationConfirmationToken = $token;
    }
    
    /**
     * Returns registration confirmation token's creation date.
     * @return string
     */
    public function getRegistrationConfirmationTokenCreationDate()
    {
        return $this->registrationConfirmationTokenCreationDate;
    }
    
    /**
     * Sets registration confirmation token's creation date.
     * @param string $date
     */
    public function setRegistrationConfirmationTokenCreationDate($date) 
    {
        $this->registrationConfirmationTokenCreationDate = $date;
    }
    
    /**
     * Returns the array of roles assigned to this user.
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * Returns the string of assigned role names.
     */
    public function getRolesAsString()
    {
        $roleList = '';
        
        $count = count($this->roles);
        $i = 0;
        foreach ($this->roles as $role) {
            $roleList .= $role->getName();
            if ($i<$count-1)
                $roleList .= ', ';
            $i++;
        }
        
        return $roleList;
    }
    
    /**
     * Assigns a role to user.
     */
    public function addRole($role)
    {
        $this->roles->add($role);
    }
    
    /**
     * Returns posts for this user.
     * @return array
     */
    public function getPosts() 
    {
        return $this->posts;
    }
    
    /**
     * Adds a new post to this user.
     * @param $post
     */
    public function addPost($post) 
    {
        $this->posts[] = $post;
    }
    
    /**
     * Returns transactions for this user.
     * @return array
     */
    public function getTransactions() 
    {
        return $this->transactions;
    }
    
    /**
     * Adds a new transaction to this user.
     * @param $transaction
     */
    public function addTransaction($transaction) 
    {
        $this->transactions[] = $transaction;
    }
    
     /**
     * Returns membership.
     * @return int     
     */
    public function getMembership() 
    {
        return $this->membership;
    }

    /**
     * Returns possible memberships as array.
     * @return array
     */
    public static function getMembershipList() 
    {
        return [
            self::MEMBERSHIP_FREE => 'Free',
            self::MEMBERSHIP_BRONZE => 'Bronze',
            self::MEMBERSHIP_SILVER => 'Silver',
            self::MEMBERSHIP_GOLD => 'Gold',
            self::MEMBERSHIP_PLATINUM => 'Platinum'
        ];
    }    
    
    /**
     * Returns user membership as string.
     * @return string
     */
    public function getMembershipAsString()
    {
        $list = self::getMembershipList();
        if (isset($list[$this->membership]))
            return $list[$this->membership];
        
        return 'Unknown';
    }    
    
    /**
     * Sets membership.
     * @param int $membership     
     */
    public function setMembership($membership) 
    {
        $this->membership = $membership;
    }   
    
    /**
     * Returns demographic for this user.
     * @return \Application\Entity\Demographic
     */
    public function getDemographic() 
    {
        return $this->demographic;
    }
    
    /**
     * Sets associated demographic.
     * @param \Applcation\Entity\Demographic $demographic
     */
    public function setDemographic($demographic) 
    {
        $this->demographic = $demographic;
        $user->setDemographic($this);
    }
    
}



