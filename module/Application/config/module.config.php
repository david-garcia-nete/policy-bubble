<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+'
                    ],
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'blog' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/blog[/:action]',
                    'defaults' => [
                        'controller'    => Controller\BlogController::class,
                        'action'        => 'index',
                    ],
                ],
            ],
            'posts' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/posts[/:action[/:id][/:step]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*',
                        'step' => '[2]'
                    ],
                    'defaults' => [
                        'controller'    => Controller\PostController::class,
                        'action'        => 'admin',
                    ],
                ],
            ],
            'images' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/images[/:action]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                    ],
                    'defaults' => [
                        'controller'    => Controller\ImageController::class,
                        'action'        => 'file',
                    ],
                ],
            ],
            'contactus' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/contactus',
                    'defaults' => [
                        'controller'    => Controller\IndexController::class,
                        'action'        => 'contactUs',
                    ],
                ],
            ],    
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
            Controller\BlogController::class => Controller\Factory\BlogControllerFactory::class,
            Controller\PostController::class => Controller\Factory\PostControllerFactory::class,
            Controller\ImageController::class => Controller\Factory\ImageControllerFactory::class,
        ],
    ],
    // The 'access_filter' key is used by the User module to restrict or permit
    // access to certain controller actions for unauthorized visitors.
    'access_filter' => [
        'options' => [
            // The access filter can work in 'restrictive' (recommended) or 'permissive'
            // mode. In restrictive mode all controller actions must be explicitly listed 
            // under the 'access_filter' config key, and access is denied to any not listed 
            // action for not logged in users. In permissive mode, if an action is not listed 
            // under the 'access_filter' key, access to it is permitted to anyone (even for 
            // not logged in users. Restrictive mode is more secure and recommended to use.
            'mode' => 'restrictive'
        ],
        'controllers' => [
            Controller\IndexController::class => [
                // Allow anyone to visit "index" action
                ['actions' => ['index', 'contactUs', 'thankYou', 'sendError'], 'allow' => '*'],
                // Allow authorized users to visit "settings" action
                ['actions' => ['settings'], 'allow' => '@']
            ],
            Controller\BlogController::class => [
                // Allow anyone to visit "index" action
                ['actions' => ['index'], 'allow' => '*']
            ],
            Controller\PostController::class => [
                ['actions' => ['add', 'view', 'admin'], 'allow' => '@'],
                ['actions' => ['edit'], 'allow' => '+post.own.edit'],
                ['actions' => ['delete'], 'allow' => '+post.own.delete']
            ],
            Controller\ImageController::class => [
                ['actions' => ['file', 'addFile', 'removeTemp', 'removeAddTemp'], 'allow' => '@']
            ],
        ]
    ],
    // This key stores configuration for RBAC manager.
    'rbac_manager' => [
        'assertions' => [Service\RbacAssertionManager::class],
    ],
    'service_manager' => [
        'factories' => [
            Service\NavManager::class => Service\Factory\NavManagerFactory::class,
            Service\RbacAssertionManager::class => Service\Factory\RbacAssertionManagerFactory::class,
            Service\PostManager::class => Service\Factory\PostManagerFactory::class,
            Service\MailSender::class => InvokableFactory::class,
            Service\ImageManager::class => InvokableFactory::class,
            Service\VideoManager::class => InvokableFactory::class,
        ],
    ],
    'session_containers' => [
        'Posts'
    ],

    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => View\Helper\Factory\MenuFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    // The following key allows to define custom styling for FlashMessenger view helper.
    'view_helper_config' => [
        'flashmessenger' => [
            'message_open_format'      => '<div%s><ul><li>',
            'message_close_string'     => '</li></ul></div>',
            'message_separator_string' => '</li><li>'
        ]
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
