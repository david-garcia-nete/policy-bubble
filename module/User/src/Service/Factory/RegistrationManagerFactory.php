<?php
namespace User\Service\Factory;

use Interop\Container\ContainerInterface;
use User\Service\RegistrationManager;

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
        $translator = $container->get('MvcTranslator');

        return new RegistrationManager($entityManager, $translator);
    }
}
