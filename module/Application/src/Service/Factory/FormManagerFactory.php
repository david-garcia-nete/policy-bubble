<?php
namespace Application\Service\Factory;
use Interop\Container\ContainerInterface;
use Application\Service\FormManager;

/**
 * This is the factory class for FormManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class FormManagerFactory
{
    /**
     * This method creates the FormManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        $translator = $container->get('MvcTranslator');
        
        return new FormManager($translator);
    }
}