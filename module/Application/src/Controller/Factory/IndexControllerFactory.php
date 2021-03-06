<?php
namespace Application\Controller\Factory;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\MailSender;
use Application\Service\MembershipManager;
use Application\Controller\IndexController;
use Application\Service\TranslationManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $mailSender = $container->get(MailSender::class);
        $membershipManager = $container->get(MembershipManager::class);
        $sessionContainer = $container->get('PayPal');
        $translationManager = $container->get(TranslationManager::class);
        
        // Instantiate the controller and inject dependencies
        return new IndexController($entityManager, $mailSender, $membershipManager, 
                $sessionContainer, $translationManager);
    }
}