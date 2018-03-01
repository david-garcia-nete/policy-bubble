<?php

namespace DoctrineORMModule\Proxy\__CG__\User\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class User extends \User\Entity\User implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', 'id', 'email', 'fullName', 'password', 'status', 'dateCreated', 'passwordResetToken', 'passwordResetTokenCreationDate', 'registrationConfirmationToken', 'registrationConfirmationTokenCreationDate', '' . "\0" . 'User\\Entity\\User' . "\0" . 'roles', 'posts', 'transactions', 'membership'];
        }

        return ['__isInitialized__', 'id', 'email', 'fullName', 'password', 'status', 'dateCreated', 'passwordResetToken', 'passwordResetTokenCreationDate', 'registrationConfirmationToken', 'registrationConfirmationTokenCreationDate', '' . "\0" . 'User\\Entity\\User' . "\0" . 'roles', 'posts', 'transactions', 'membership'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (User $proxy) {
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
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', []);

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', [$email]);

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getFullName()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getFullName', []);

        return parent::getFullName();
    }

    /**
     * {@inheritDoc}
     */
    public function setFullName($fullName)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setFullName', [$fullName]);

        return parent::setFullName($fullName);
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
    public function getStatusAsString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatusAsString', []);

        return parent::getStatusAsString();
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
    public function getPassword()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPassword', []);

        return parent::getPassword();
    }

    /**
     * {@inheritDoc}
     */
    public function setPassword($password)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPassword', [$password]);

        return parent::setPassword($password);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateCreated()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateCreated', []);

        return parent::getDateCreated();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateCreated($dateCreated)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateCreated', [$dateCreated]);

        return parent::setDateCreated($dateCreated);
    }

    /**
     * {@inheritDoc}
     */
    public function getResetPasswordToken()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResetPasswordToken', []);

        return parent::getResetPasswordToken();
    }

    /**
     * {@inheritDoc}
     */
    public function setPasswordResetToken($token)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPasswordResetToken', [$token]);

        return parent::setPasswordResetToken($token);
    }

    /**
     * {@inheritDoc}
     */
    public function getPasswordResetTokenCreationDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPasswordResetTokenCreationDate', []);

        return parent::getPasswordResetTokenCreationDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setPasswordResetTokenCreationDate($date)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPasswordResetTokenCreationDate', [$date]);

        return parent::setPasswordResetTokenCreationDate($date);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationConfirmationToken()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationConfirmationToken', []);

        return parent::getRegistrationConfirmationToken();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationConfirmationToken($token)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationConfirmationToken', [$token]);

        return parent::setRegistrationConfirmationToken($token);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegistrationConfirmationTokenCreationDate()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegistrationConfirmationTokenCreationDate', []);

        return parent::getRegistrationConfirmationTokenCreationDate();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegistrationConfirmationTokenCreationDate($date)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegistrationConfirmationTokenCreationDate', [$date]);

        return parent::setRegistrationConfirmationTokenCreationDate($date);
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRoles', []);

        return parent::getRoles();
    }

    /**
     * {@inheritDoc}
     */
    public function getRolesAsString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRolesAsString', []);

        return parent::getRolesAsString();
    }

    /**
     * {@inheritDoc}
     */
    public function addRole($role)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addRole', [$role]);

        return parent::addRole($role);
    }

    /**
     * {@inheritDoc}
     */
    public function getPosts()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPosts', []);

        return parent::getPosts();
    }

    /**
     * {@inheritDoc}
     */
    public function addPost($post)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addPost', [$post]);

        return parent::addPost($post);
    }

    /**
     * {@inheritDoc}
     */
    public function getTransactions()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTransactions', []);

        return parent::getTransactions();
    }

    /**
     * {@inheritDoc}
     */
    public function addTransaction($transaction)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTransaction', [$transaction]);

        return parent::addTransaction($transaction);
    }

    /**
     * {@inheritDoc}
     */
    public function getMembership()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMembership', []);

        return parent::getMembership();
    }

    /**
     * {@inheritDoc}
     */
    public function getMembershipAsString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMembershipAsString', []);

        return parent::getMembershipAsString();
    }

    /**
     * {@inheritDoc}
     */
    public function setMembership($membership)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMembership', [$membership]);

        return parent::setMembership($membership);
    }

}
