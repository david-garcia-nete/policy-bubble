<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\RegistrationManager;
use User\Service\UserManager;

/**
 * This is the factory class for RegistrationManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class RegistrationManagerFactory
{
    /**
     * This method creates the RegistrationManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userManager = $container->get(UserManager::class);
        
        return new RegistrationManager($entityManager, $userManager);
    }
}
