<?php
namespace Application\Service\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\PostManager;
use Application\Service\MembershipManager;
use Application\Service\GeoPlugin;
/**
 * This is the factory for PostManager. Its purpose is to instantiate the
 * service.
 */
class PostManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $membershipManager = $container->get(MembershipManager::class);
        $geoPlugin = $container->get(GeoPlugin::class);
        
        // Instantiate the service and inject dependencies
        return new PostManager($entityManager, $membershipManager, $geoPlugin);
    }
}
