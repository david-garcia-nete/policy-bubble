<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\MailSender;
use Application\Controller\SettingsController;
use Application\Service\SettingsManager;
use User\Service\AuthManager;
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
        $mailSender = $container->get(MailSender::class);
        
        // Instantiate the controller and inject dependencies
        return new SettingsController($entityManager, $settingsManager, $authManager, $mailSender);
    }
}