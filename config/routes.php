
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
+
+    $routes->prefix('api', function (RouteBuilder $builder): void {
+        $builder->setExtensions(['json']);
+        $builder->setRouteClass(DashedRoute::class);
+
+        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login'])
+            ->setMethods(['POST']);
+
+        $builder->connect('/profile', ['controller' => 'Users', 'action' => 'profile'])
+            ->setMethods(['GET']);
+
+        $builder->connect('/orders', ['controller' => 'MillingOrders', 'action' => 'index'])
+            ->setMethods(['GET']);
+
+        $builder->connect('/orders/:id', ['controller' => 'MillingOrders', 'action' => 'view'])
+            ->setPass(['id'])
+            ->setMethods(['GET'])
+            ->setPatterns(['id' => '\\d+']);
+
+        $builder->connect('/orders', ['controller' => 'MillingOrders', 'action' => 'add'])
+            ->setMethods(['POST']);
+
+        $builder->fallbacks(DashedRoute::class);
+    });
 };

