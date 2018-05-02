<?php
namespace Application\Service\Factory;

use Interop\Container\ContainerInterface;
use Application\Service\SettingsManager;

/**
 * This is the factory class for SettingsManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class SettingsManagerFactory
{
    /**
     * This method creates the RegistrationManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $translator = $container->get('MvcTranslator');

        return new SettingsManager($entityManager, $translator);
    }
}
