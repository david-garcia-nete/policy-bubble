<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\PostManager;
use Application\Controller\BlogController;
use Application\Service\ImageManager;
/**
 * This is the factory for BlogController. Its purpose is to instantiate the
 * controller.
 */
class BlogControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $postManager = $container->get(PostManager::class);
        $imageManager = $container->get(ImageManager::class);
        
        // Instantiate the controller and inject dependencies
        return new BlogController($entityManager, $postManager, $imageManager);
    }
}