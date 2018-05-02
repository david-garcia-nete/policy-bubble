<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\PostManager;
use Application\Controller\PostController;
use Application\Service\ImageManager;
use Application\Service\VideoManager;
use Application\Service\AudioManager;

/**
 * This is the factory for PostController. Its purpose is to instantiate the
 * controller.
 */
class PostControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        $imageManager = $container->get(ImageManager::class);
        $videoManager = $container->get(VideoManager::class);
        $audioManager = $container->get(AudioManager::class);
        $sessionContainer = $container->get('Posts');
        $translator = $container->get('MvcTranslator');
        
        // Instantiate the controller and inject dependencies
        return new PostController($entityManager, $postManager, $imageManager, 
                $videoManager, $audioManager, $sessionContainer, $translator);
    }
}