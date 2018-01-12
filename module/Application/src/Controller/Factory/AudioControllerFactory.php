<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\AudioManager;
use Application\Controller\AudioController;

/**
 * This is the factory for ImageController. Its purpose is to instantiate the
 * controller.
 */
class AudioControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $audioManager = $container->get(AudioManager::class);
        
        // Instantiate the controller and inject dependencies
        return new AudioController($audioManager);
    }
}

