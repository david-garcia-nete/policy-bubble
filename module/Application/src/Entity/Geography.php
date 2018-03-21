<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents geography information for a post.
 * @ORM\Entity
 * @ORM\Table(name="geography")
 */
class Geography 
{
       
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;
    
    /**
     * @ORM\OneToOne(targetEntity="Application\Entity\Post", inversedBy="geography")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id")
     */
    protected $post;
    
    /** 
     * @ORM\Column(name="request")  
     */
    protected $request;
    
    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /** 
     * @ORM\Column(name="credit")  
     */
    protected $credit;
    
    /** 
     * @ORM\Column(name="city")  
     */
    protected $city;
    
    /** 
     * @ORM\Column(name="region")  
     */
    protected $region;
    
    /** 
     * @ORM\Column(name="area_code")  
     */
    protected $areaCode;
    
    /** 
     * @ORM\Column(name="dma_code")  
     */
    protected $dmaCode;
    
    /** 
     * @ORM\Column(name="country_code")  
     */
    protected $countryCode;
    
    /** 
     * @ORM\Column(name="country_name")  
     */
    protected $countryName;
    
    /** 
     * @ORM\Column(name="continent_code")  
     */
    protected $continentCode;
    
    /** 
     * @ORM\Column(name="latitude")  
     */
    protected $latitude;
    
    /** 
     * @ORM\Column(name="longitude")  
     */
    protected $longitude;
    
    /** 
     * @ORM\Column(name="region_code")  
     */
    protected $regionCode;
    
    /** 
     * @ORM\Column(name="region_name")  
     */
    protected $regionName;
    
    /** 
     * @ORM\Column(name="currency_code")  
     */
    protected $currencyCode;
    
    /** 
     * @ORM\Column(name="currency_symbol")  
     */
    protected $currencySymbol;
    
    /** 
     * @ORM\Column(name="currency_symbol_utf8")  
     */
    protected $currencySymbolUtf8;
    
    /** 
     * @ORM\Column(name="currency_converter")  
     */
    protected $currencyConverter;

    /**
     * Returns ID of this geographic.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets ID of this geographic.
     * @param int $id
     */
    public function setId($id) 
    {
        $this->id = $id;
    }
    
    /*
     * Returns associated post.
     * @return \Application\Entity\Post
     */
    public function getPost() 
    {
        return $this->post;
    }
    
    /**
     * Sets post.
     * @param \Application\Entity\Post $post
     */
    public function setPost($post) 
    {
        $this->post = $post;
        $post->setGeography($this);
    }
    
    /**
     * Returns request.
     * @return string
     */
    public function getRequest() 
    {
       return $this->request; 
    }
    
    /**
     * Sets request.     
     * @param string $request
     */
    public function setRequest($request) 
    {
        $this->request = $request;
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
     * Sets status.     
     * @param int $status
     */
    public function setStatus($status) 
    {
        $this->status = $status;
    }
    
    /**
     * Returns credit.
     * @return string
     */
    public function getCredit() 
    {
       return $this->credit; 
    }
    
    /**
     * Sets credit.     
     * @param string $credit
     */
    public function setCredit($credit) 
    {
        $this->credit = $credit;
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
     * Returns region.
     * @return string
     */
    public function getRegion() 
    {
       return $this->region; 
    }
    
    /**
     * Sets region.     
     * @param string $region
     */
    public function setRegion($region) 
    {
        $this->region = $region;
    }    
    
    /**
     * Returns area code.
     * @return string
     */
    public function getAreaCode() 
    {
       return $this->areaCode; 
    }
    
    /**
     * Sets area code.     
     * @param string $areaCode
     */
    public function setAreaCode($areaCode) 
    {
        $this->areaCode = $areaCode;
    }
    
    /**
     * Returns dma code.
     * @return string
     */
    public function getDmaCode() 
    {
       return $this->dmaCode; 
    }
    
    /**
     * Sets dma code.     
     * @param string $dmaCode
     */
    public function setDmaCode($dmaCode) 
    {
        $this->dmaCode = $dmaCode;
    }
    
    
    /**
     * Returns country code.
     * @return string
     */
    public function getCountryCode() 
    {
       return $this->countryCode; 
    }
    
    /**
     * Sets country code.     
     * @param string $countryCode
     */
    public function setCountryCode($countryCode) 
    {
        $this->countryCode = $countryCode;
    }

    
        
    /**
     * Returns country name.
     * @return string
     */
    public function getCountryName() 
    {
       return $this->countryName; 
    }
    
    /**
     * Sets country name.     
     * @param string $countryName
     */
    public function setCountryName($countryName) 
    {
        $this->countryName = $countryName;
    }

    /**
     * Returns continent code.
     * @return string
     */
    public function getContinentCode() 
    {
       return $this->continentCode; 
    }
    
    /**
     * Sets continent code.     
     * @param string $continentCode
     */
    public function setContinentCode($continentCode) 
    {
        $this->continentCode = $continentCode;
    }
    
    /**
     * Returns latitude.
     * @return string
     */
    public function getLatitude() 
    {
       return $this->latitude; 
    }
    
    /**
     * Sets latitude.     
     * @param string $latitude
     */
    public function setLatitude($latitude) 
    {
        $this->latitude = $latitude;
    }
    
    /**
     * Returns longitude.
     * @return string
     */
    public function getLongitude() 
    {
       return $this->longitude; 
    }
    
    /**
     * Sets longitude.     
     * @param string $longitude
     */
    public function setLongitude($longitude) 
    {
        $this->longitude = $longitude;
    }

    /**
     * Returns region code.
     * @return string
     */
    public function getRegionCode() 
    {
       return $this->regionCode; 
    }
    
    /**
     * Sets region code.     
     * @param string $regionCode
     */
    public function setRegionCode($regionCode) 
    {
        $this->regionCode = $regionCode;
    }    
    
    /**
     * Returns region name.
     * @return string
     */
    public function getRegionName() 
    {
       return $this->regionName; 
    }
    
    /**
     * Sets region name.     
     * @param string $regionName
     */
    public function setRegionName($regionName) 
    {
        $this->regionName = $regionName;
    }    

    /**
     * Returns currency code.
     * @return string
     */
    public function getCurrencyCode() 
    {
       return $this->currencyCode; 
    }
    
    /**
     * Sets currency code.     
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode) 
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * Returns currency symbol.
     * @return string
     */
    public function getCurrencySymbol() 
    {
       return $this->currencySymbol; 
    }
    
    /**
     * Sets currency symbol.     
     * @param string $currencySymbol
     */
    public function setCurrencySymbol($currencySymbol) 
    {
        $this->currencySymbol = $currencySymbol;
    }

    /**
     * Returns currency symbol utf8.
     * @return string
     */
    public function getCurrencySymbolUtf8() 
    {
       return $this->currencySymbolUtf8; 
    }
    
    /**
     * Sets currency symbol utf8.     
     * @param string $currencySymbolUtf8
     */
    public function setCurrencySymbolUtf8($currencySymbolUtf8) 
    {
        $this->currencySymbolUtf8 = $currencySymbolUtf8;
    }

    /**
     * Returns currency converter.
     * @return string
     */
    public function getCurrencyConverter() 
    {
       return $this->currencyConverter; 
    }
    
    /**
     * Sets currency converter.     
     * @param string $currencyConverter
     */
    public function setCurrencyConverter($currencyConverter) 
    {
        $this->currencyConverter = $currencyConverter;
    }    
    
}

