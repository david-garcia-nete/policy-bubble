<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single membership transaction.
 * @ORM\Entity
 * @ORM\Table(name="transactions_paypal")
 */
class TransactionsPayPal 
{
    
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
        
    
}

