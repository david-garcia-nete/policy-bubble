<?php
namespace User\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use User\Controller\RegistrationController;
use User\Service\RegistrationManager;

/**
 * This is the factory for RegistrationController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class RegistrationControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $registrationManager = $container->get(RegistrationManager::class);
        
        // Instantiate the controller and inject dependencies
        return new RegistrationController($entityManager, $registrationManager);
    }
}