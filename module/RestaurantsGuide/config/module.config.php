<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace RestaurantsGuide;

use Application\Controller\IndexController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'restaurants' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\RestaurantsGuideController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'api-restaurants' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/api/restaurants',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\RestaurantsApiController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\RestaurantsGuideController::class => InvokableFactory::class,
            Controller\RestaurantsApiController::class => Controller\RestaurantsApiFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'restaurants' => __DIR__ . '/../view',
        ],
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
        ]
    ],
];
