<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Geography extends \Application\Entity\Geography implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = [];



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', 'post', 'request', 'status', 'credit', 'city', 'region', 'areaCode', 'dmaCode', 'countryCode', 'countryName', 'continentCode', 'latitude', 'longitude', 'regionCode', 'regionName', 'currencyCode', 'currencySymbol', 'currencySymbolUtf8', 'currencyConverter'];
        }

        return ['__isInitialized__', 'id', 'post', 'request', 'status', 'credit', 'city', 'region', 'areaCode', 'dmaCode', 'countryCode', 'countryName', 'continentCode', 'latitude', 'longitude', 'regionCode', 'regionName', 'currencyCode', 'currencySymbol', 'currencySymbolUtf8', 'currencyConverter'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Geography $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setId($id)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setId', [$id]);

        return parent::setId($id);
    }

    /**
     * {@inheritDoc}
     */
    public function getPost()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPost', []);

        return parent::getPost();
    }

    /**
     * {@inheritDoc}
     */
    public function setPost($post)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPost', [$post]);

        return parent::setPost($post);
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRequest', []);

        return parent::getRequest();
    }

    /**
     * {@inheritDoc}
     */
    public function setRequest($request)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRequest', [$request]);

        return parent::setRequest($request);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatus()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatus', []);

        return parent::getStatus();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatus($status)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatus', [$status]);

        return parent::setStatus($status);
    }

    /**
     * {@inheritDoc}
     */
    public function getCredit()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCredit', []);

        return parent::getCredit();
    }

    /**
     * {@inheritDoc}
     */
    public function setCredit($credit)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCredit', [$credit]);

        return parent::setCredit($credit);
    }

    /**
     * {@inheritDoc}
     */
    public function getCity()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCity', []);

        return parent::getCity();
    }

    /**
     * {@inheritDoc}
     */
    public function setCity($city)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCity', [$city]);

        return parent::setCity($city);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegion()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegion', []);

        return parent::getRegion();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegion($region)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegion', [$region]);

        return parent::setRegion($region);
    }

    /**
     * {@inheritDoc}
     */
    public function getAreaCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAreaCode', []);

        return parent::getAreaCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setAreaCode($areaCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAreaCode', [$areaCode]);

        return parent::setAreaCode($areaCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getDmaCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDmaCode', []);

        return parent::getDmaCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setDmaCode($dmaCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDmaCode', [$dmaCode]);

        return parent::setDmaCode($dmaCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountryCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountryCode', []);

        return parent::getCountryCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setCountryCode($countryCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCountryCode', [$countryCode]);

        return parent::setCountryCode($countryCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getCountryName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCountryName', []);

        return parent::getCountryName();
    }

    /**
     * {@inheritDoc}
     */
    public function setCountryName($countryName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCountryName', [$countryName]);

        return parent::setCountryName($countryName);
    }

    /**
     * {@inheritDoc}
     */
    public function getContinentCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContinentCode', []);

        return parent::getContinentCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setContinentCode($continentCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setContinentCode', [$continentCode]);

        return parent::setContinentCode($continentCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getLatitude()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLatitude', []);

        return parent::getLatitude();
    }

    /**
     * {@inheritDoc}
     */
    public function setLatitude($latitude)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLatitude', [$latitude]);

        return parent::setLatitude($latitude);
    }

    /**
     * {@inheritDoc}
     */
    public function getLongitude()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLongitude', []);

        return parent::getLongitude();
    }

    /**
     * {@inheritDoc}
     */
    public function setLongitude($longitude)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLongitude', [$longitude]);

        return parent::setLongitude($longitude);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegionCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegionCode', []);

        return parent::getRegionCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegionCode($regionCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegionCode', [$regionCode]);

        return parent::setRegionCode($regionCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegionName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegionName', []);

        return parent::getRegionName();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegionName($regionName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegionName', [$regionName]);

        return parent::setRegionName($regionName);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrencyCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCurrencyCode', []);

        return parent::getCurrencyCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrencyCode($currencyCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCurrencyCode', [$currencyCode]);

        return parent::setCurrencyCode($currencyCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrencySymbol()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCurrencySymbol', []);

        return parent::getCurrencySymbol();
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrencySymbol($currencySymbol)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCurrencySymbol', [$currencySymbol]);

        return parent::setCurrencySymbol($currencySymbol);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrencySymbolUtf8()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCurrencySymbolUtf8', []);

        return parent::getCurrencySymbolUtf8();
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrencySymbolUtf8($currencySymbolUtf8)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCurrencySymbolUtf8', [$currencySymbolUtf8]);

        return parent::setCurrencySymbolUtf8($currencySymbolUtf8);
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrencyConverter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCurrencyConverter', []);

        return parent::getCurrencyConverter();
    }

    /**
     * {@inheritDoc}
     */
    public function setCurrencyConverter($currencyConverter)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCurrencyConverter', [$currencyConverter]);

        return parent::setCurrencyConverter($currencyConverter);
    }

}
