<?php
namespace Application\Service;
use User\Entity\User;
//use Application\Entity\TransactionPaypal;
use Zend\Filter\StaticFilter;

class MembershipManager
{
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager;
     */
    private $entityManager;
    
    /**
     * Constructor.
     */
    public function __construct($entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
   
}
