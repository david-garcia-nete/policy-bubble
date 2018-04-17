<?php
namespace Application\Service;
use User\Entity\User;
/**
 * This service is responsible for determining which items should be in the main menu.
 * The items may be different depending on whether the user is authenticated or not.
 */
class NavManager
{
    /**
     * Auth service.
     * @var Zend\Authentication\Authentication
     */
    private $authService;
    
    /**
     * Url view helper.
     * @var Zend\View\Helper\Url
     */
    private $urlHelper;
    
    /**
     * RBAC manager.
     * @var User\Service\RbacManager
     */
    private $rbacManager;
    
    /**
     * Entity manager.
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Session container.
     * @var Zend\Session\Container
     */
    private $sessionContainer;
    
    /**
     * Constructs the service.
     */
    public function __construct($authService, $urlHelper, $rbacManager, $entityManager,
            $sessionContainer) 
    {
        $this->authService = $authService;
        $this->urlHelper = $urlHelper;
        $this->rbacManager = $rbacManager;
        $this->entityManager = $entityManager;
        $this->sessionContainer = $sessionContainer;
    }
    
    /**
     * This method returns menu items depending on whether user has logged in or not.
     */
    public function getMenuItems() 
    {
        $url = $this->urlHelper;
        $items = [];
        
        $homeDropdownItems = [];
        
        $homeDropdownItems[] = [
            'id' => 'about',
            'label' => 'About',
            'link'  => $url('about')
        ];
        
//        $homeDropdownItems[] = [
//            'id' => 'membership',
//            'label' => 'Membership',
//            'link'  => $url('membership')
//        ];
        
        $homeDropdownItems[] = [
            'id' => 'contactUs',
            'label' => 'Feedback',
            'link'  => $url('contactus')
        ];
        
        $items[] = [
            'id' => 'home',
            'label' => 'Home',
            'link'  => $url('home'),
            'dropdown' => $homeDropdownItems
            
        ];
                
        $items[] = [
            'id' => 'blog',
            'label' => 'Blog',
            'link'  => $url('blog')
            ];
        
        $items[] = [
            'id' => 'posts',
            'label' => 'Posts',
            'link' => $url('posts')
            ];
        
        $items[] = [
            'id' => 'analysis',
            'label' => 'Analysis',
            'link' => $url('analysis')
            ];
                
        // Display "Login" menu item for not authorized user only. On the other hand,
        // display "Admin" and "Logout" menu items only for authorized users.
        if (!$this->authService->hasIdentity()) {
            
            $this->sessionContainer->Language = null;
            
            $items[] = [
                'id' => 'register',
                'label' => 'Register',
                'link'  => $url('registration'),
                'float' => 'right'
            ];
            $items[] = [
                'id' => 'login',
                'label' => 'Sign in',
                'link'  => $url('login'),
                'float' => 'right'
            ];
        } else {
            
            // Determine which items must be displayed in Admin dropdown.
            $adminDropdownItems = [];
            
            if ($this->rbacManager->isGranted(null, 'user.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'users',
                            'label' => 'Manage Users',
                            'link' => $url('users')
                        ];
            }
            
            if ($this->rbacManager->isGranted(null, 'permission.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'permissions',
                            'label' => 'Manage Permissions',
                            'link' => $url('permissions')
                        ];
            }
            
            if ($this->rbacManager->isGranted(null, 'role.manage')) {
                $adminDropdownItems[] = [
                            'id' => 'roles',
                            'label' => 'Manage Roles',
                            'link' => $url('roles')
                        ];
            }
            
            if (count($adminDropdownItems)!=0) {
                $items[] = [
                    'id' => 'admin',
                    'label' => 'Admin',
                    'dropdown' => $adminDropdownItems
                ];
            }
            
            $user = $this->entityManager->getRepository(User::class)
                    ->findOneBy(['email' => $this->authService->getIdentity()]);
            
            $this->sessionContainer->Language = $user->getLanguage();
                
            $items[] = [
                'id' => 'logout',
                'label' => $user->getFullName(),
                'float' => 'right',
                'dropdown' => [
                    [
                        'id' => 'settings',
                        'label' => 'Settings',
                        'link' => $url('settings')
                    ],
                    [
                        'id' => 'logout',
                        'label' => 'Sign out',
                        'link' => $url('logout')
                    ],
                ]
            ];
        }
        
        return $items;
    }
}