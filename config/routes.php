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
};