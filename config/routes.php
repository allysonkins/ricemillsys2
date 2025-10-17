<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {
        // AJAX endpoints for payments
        $builder->connect(
            '/payments/getMillingOrdersByCustomer/:userId', // Changed from customerId to userId
            ['controller' => 'Payments', 'action' => 'getMillingOrdersByCustomer']
        )->setPass(['userId']); // Changed from customerId to userId

        $builder->connect(
            '/payments/getCustomerByMillingOrder/:millingOrderId',
            ['controller' => 'Payments', 'action' => 'getCustomerByMillingOrder']
        )->setPass(['millingOrderId']);

        // Default redirect
        $builder->connect('/', ['controller' => 'Users', 'action' => 'redirectHome']);

        $builder->fallbacks(DashedRoute::class);
    });

    $apiRoutes = function (RouteBuilder $builder): void {
        $builder->setExtensions(['json']);
        $builder->setRouteClass(DashedRoute::class);

        $builder->connect('/csrf', ['controller' => 'Users', 'action' => 'csrf']);
        $builder->connect('/users/csrf', ['controller' => 'Users', 'action' => 'csrf']);
        $builder->connect('/users/csrf-token', ['controller' => 'Users', 'action' => 'csrf']);

        $builder->connect(
            '/login',
            ['controller' => 'Users', 'action' => 'login', '_method' => 'POST']
        );

        $builder->connect(
            '/users/login',
            ['controller' => 'Users', 'action' => 'login', '_method' => 'POST']
        );

        $builder->connect(
            '/logout',
            ['controller' => 'Users', 'action' => 'logout', '_method' => 'POST']
        );

        $builder->connect(
            '/users/logout',
            ['controller' => 'Users', 'action' => 'logout', '_method' => 'POST']
        );

        $builder->connect('/profile', ['controller' => 'Users', 'action' => 'profile']);
        $builder->connect('/users/profile', ['controller' => 'Users', 'action' => 'profile']);

        $builder->connect('/users', ['controller' => 'Users', 'action' => 'index']);

        $builder->connect(
            '/users/add',
            ['controller' => 'Users', 'action' => 'add', '_method' => 'POST']
        );

        $builder->connect(
            '/users/edit/:id',
            ['controller' => 'Users', 'action' => 'edit'],
            ['pass' => ['id'], 'id' => '[0-9]+']
        );

        $builder->connect(
            '/users/delete/:id',
            ['controller' => 'Users', 'action' => 'delete'],
            ['pass' => ['id'], 'id' => '[0-9]+']
        );

        $builder->connect('/users/customers', ['controller' => 'Users', 'action' => 'customers']);

        $builder->connect('/orders', ['controller' => 'MillingOrders', 'action' => 'index']);

        $builder->connect(
            '/orders/:id',
            ['controller' => 'MillingOrders', 'action' => 'view'],
            ['pass' => ['id'], 'id' => '[0-9]+']
        );

        $builder->connect(
            '/orders',
            ['controller' => 'MillingOrders', 'action' => 'add', '_method' => 'POST']
        );

        $builder->fallbacks(DashedRoute::class);
    };

    $routes->prefix('api', $apiRoutes);

    $routes->scope('/User', function (RouteBuilder $scope) use ($apiRoutes): void {
        $scope->prefix('api', $apiRoutes);
    });
};