<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\VideoManager;
use Application\Controller\VideoController;

/**
 * This is the factory for ImageController. Its purpose is to instantiate the
 * controller.
 */
class VideoControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $videoManager = $container->get(VideoManager::class);
        
        // Instantiate the controller and inject dependencies
        return new VideoController($videoManager);
    }
}

