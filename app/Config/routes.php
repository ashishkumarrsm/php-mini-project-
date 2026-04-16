<?php
/**
 * Application routes
 */

Router::connect('/', array(
    'controller' => 'products',
    'action' => 'index'
));

Router::connect('/pages/*', array(
    'controller' => 'pages',
    'action' => 'display'
));

Router::connect(
    '/shop/:slug',
    array(
        'controller' => 'products',
        'action' => 'view'
    ),
    array(
        'pass' => array('slug'),
        'slug' => '[a-z0-9\-]+'
    )
);

Router::connect('/cart', array(
    'controller' => 'carts',
    'action' => 'view'
));

Router::connect('/checkout', array(
    'controller' => 'orders',
    'action' => 'checkout'
));

Router::connect('/admin/login', array(
    'controller' => 'users',
    'action' => 'login',
    'admin' => true
));

Router::connect('/admin/register', array(
    'controller' => 'users',
    'action' => 'register',
    'admin' => true
));

Router::connect('/admin', array(
    'controller' => 'dashboard',
    'action' => 'index',
    'admin' => true
));

Router::connect(
    '/admin/:controller/:action/*',
    array('prefix' => 'admin', 'admin' => true)
);

CakePlugin::routes();

require CAKE . 'Config' . DS . 'routes.php';
