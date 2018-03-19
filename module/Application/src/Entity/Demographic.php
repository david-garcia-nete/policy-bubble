<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a single demographic object.
 * @ORM\Entity
 * @ORM\Table(name="transactions_paypal")
 */
class Demographic 
{
    
    
    const GENDER_FEMALE   = 1;   
    const GENDER_MALE     = 2;    

    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="User\Entity\User", inversedBy="demographic")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /** 
     * @ORM\Column(name="country")  
     */
    protected $country;
    
    /** 
     * @ORM\Column(name="state")  
     */
    protected $state;
    
    /** 
     * @ORM\Column(name="city")  
     */
    protected $city;
    
    /**
     * @ORM\Column(name="gender")  
     */
    protected $gender;
    
    /** 
     * @ORM\Column(name="birth_year")  
     */
    protected $birthYear;
    
    
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
     * @return \User\Entity\User
     */
    public function getUser() 
    {
        return $this->user;
    }
    
    /**
     * Sets associated user.
     * @param \User\Entity\User $user
     */
    public function setUser($user) 
    {
        $this->user = $user;
        $user->setDemographic($this);
    }
    
    /**
     * Returns country.
     * @return string
     */
    public function getCountry() 
    {
       return $this->country; 
    }
    
    /**
     * Sets country.     
     * @param string $country
     */
    public function setCountry($country) 
    {
        $this->coutry = $country;
    }
    
    /**
     * Returns state.
     * @return string
     */
    public function getState() 
    {
       return $this->state; 
    }
    
    /**
     * Sets state.     
     * @param string $state
     */
    public function setState($state) 
    {
        $this->state = $state;
    }
    
    /**
     * Returns city.
     * @return string
     */
    public function getCity() 
    {
       return $this->city; 
    }
    
    /**
     * Sets city.     
     * @param string $city
     */
    public function setCity($city) 
    {
        $this->city = $city;
    }
       
      /**
     * Returns gender.
     * @return int     
     */
    public function getGender() 
    {
        return $this->gender;
    }

    /**
     * Returns possible genders as array.
     * @return array
     */
    public static function getGenderList() 
    {
        return [
            self::GENDER_FEMALE => 'Female',
            self::GENDER_MALE => 'Male'
        ];
    }    
    
    /**
     * Returns demographic gender as string.
     * @return string
     */
    public function getGenderAsString()
    {
        $list = self::getGenderList();
        if (isset($list[$this->gender]))
            return $list[$this->gender];
        
        return 'Unknown';
    }    
    
    /**
     * Sets gender.
     * @param int $gender     
     */
    public function setGender($gender) 
    {
        $this->gender = $gender;
    }   
    
    
    /**
     * Returns birth year.
     * @return integer
     */
    public function getBirthYear() 
    {
        return $this->birthYear;
    }

    /**
     * Sets birth year.
     * @param int $birthYear
     */
    public function setBirthYear($birthYear) 
    {
        $this->birthYear = $birthYear;
    }
    
  
  
    
}

