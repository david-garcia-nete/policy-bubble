<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\MailSender;
use Application\Controller\SettingsController;
use Application\Service\SettingsManager;
use User\Service\AuthManager;
use Application\Service\PostManager;
use Application\Service\ImageManager;
use Application\Service\VideoManager;
use Application\Service\AudioManager;
use User\Service\UserManager;

/**
 * This is the factory for SettingsController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class SettingsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $settingsManager = $container->get(SettingsManager::class);
        $authManager = $container->get(AuthManager::class);
        $postManager = $container->get(PostManager::class);
        $imageManager = $container->get(ImageManager::class);
        $videoManager = $container->get(VideoManager::class);
        $audioManager = $container->get(AudioManager::class);
        $mailSender = $container->get(MailSender::class);
        $sessionContainer = $container->get('Language');
        $translator = $container->get('MvcTranslator');
        $userManager = $container->get(UserManager::class);
        
        // Instantiate the controller and inject dependencies
        return new SettingsController($entityManager, $settingsManager, 
                $authManager, $postManager, $imageManager, 
                $videoManager, $audioManager, $mailSender, $sessionContainer, 
                $translator, $userManager);
    }
}