<?php
namespace Application\Service\Factory;
use Interop\Container\ContainerInterface;
use Application\Service\NavManager;
use User\Service\RbacManager;

/**
 * This is the factory class for NavManager service. The purpose of the factory
 * is to instantiate the service and pass it dependencies (inject dependencies).
 */
class NavManagerFactory
{
    /**
     * This method creates the NavManager service and returns its instance. 
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {        
        $authService = $container->get(\Zend\Authentication\AuthenticationService::class);
        
        $viewHelperManager = $container->get('ViewHelperManager');
        $urlHelper = $viewHelperManager->get('url');
        $rbacManager = $container->get(RbacManager::class);
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sessionContainer = $container->get('Language');
        $translator = $container->get('MvcTranslator');
        
        return new NavManager($authService, $urlHelper, $rbacManager, $entityManager,
                $sessionContainer, $translator);
    }
}