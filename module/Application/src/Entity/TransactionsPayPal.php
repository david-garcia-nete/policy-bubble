<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single membership transaction.
 * @ORM\Entity(repositoryClass="\Application\Repository\TransactionsPayPalRepository")
 * @ORM\Table(name="transactions_paypal")
 */
class TransactionsPayPal 
{
    
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
     * @ORM\ManyToOne(targetEntity="User\Entity\User", inversedBy="transactions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /** 
     * @ORM\Column(name="payment_id")  
     */
    protected $paymentId;
    
    /** 
     * @ORM\Column(name="hash")  
     */
    protected $hash;
    
    /** 
     * @ORM\Column(name="complete")  
     */
    protected $complete;
    
    /** 
     * @ORM\Column(name="membership")  
     */
    protected $membership;
    
    /**
     * @ORM\Column(name="date_created")  
     */
    protected $dateCreated;
    
    /**
     * @ORM\Column(name="date_completed")  
     */
    protected $dateCompleted;
    
    /** 
     * @ORM\Column(name="amount")  
     */
    protected $amount;
    

    /**
     * Returns ID of this post.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets ID of this post.
     * @param int $id
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

     /*
     * Returns associated user.
     * @return \Application\Entity\User
     */
    public function getUser() 
    {
        return $this->user;
    }
    
    /**
     * Sets associated user.
     * @param \Application\Entity\User $user
     */
    public function setUser($user) 
    {
        $this->user = $user;
        $user->addTransaction($this);
    }
    
    /**
     * Returns payment ID.
     * @return string
     */
    public function getPaymentId() 
    {
       return $this->paymentId; 
    }
    
    /**
     * Sets payment ID.     
     * @param string $paymentId
     */
    public function setPaymentId($paymentId) 
    {
        $this->paymentId = $paymentId;
    }
    
    /**
     * Returns hash.
     * @return string
     */
    public function getHash() 
    {
       return $this->hash; 
    }
    
    /**
     * Sets hash.     
     * @param string $hash
     */
    public function setHash($hash) 
    {
        $this->hash = $hash;
    }
    
    /**
     * Returns complete status.
     * @return integer
     */
    public function getComplete() 
    {
        return $this->complete;
    }

    /**
     * Sets complete status.
     * @param int $complete
     */
    public function setComplete($complete) 
    {
        $this->complete = $complete;
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
     * Returns transaction membership as string.
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
     * Returns the date of transaction creation.
     * @return string     
     */
    public function getDateCreated() 
    {
        return $this->dateCreated;
    }
    
    /**
     * Sets the date when this transaction was created.
     * @param string $dateCreated     
     */
    public function setDateCreated($dateCreated) 
    {
        $this->dateCreated = $dateCreated;
    }    
    
    /**
     * Returns the date of transaction completion.
     * @return string     
     */
    public function getDateCompleted() 
    {
        return $this->dateCompleted;
    }
    
    /**
     * Sets the date when this transaction was completed.
     * @param string $dateCompleted     
     */
    public function setDateCompleted($dateCompleted) 
    {
        $this->dateCompleted= $dateCompleted;
    }    
    
     /**
     * Returns amount.
     * @return float     
     */
    public function getAmount() 
    {
        return $this->amount;
    }
    
     /**
     * Sets amount.
     * @param float $amount   
     */
    public function setAmount($amount) 
    {
        $this->amount = $amount;
    }   
        
    
}

