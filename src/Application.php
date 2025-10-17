<?php
declare(strict_types=1);

namespace App;

use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;

// Older CakePHP releases (such as 4.3) located BaseApplication in Cake\Core.
// Provide a runtime alias so the class can still be located when the project
// is installed alongside those dependencies (e.g. in legacy PHP 7.4 stacks).
if (!class_exists(\Cake\Http\BaseApplication::class) && class_exists(\Cake\Core\BaseApplication::class)) {
    class_alias(\Cake\Core\BaseApplication::class, \Cake\Http\BaseApplication::class);
}

// Authentication imports
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add('Table', (new TableLocator())->allowFallbackClass(false));
        }

        // Load Authentication plugin
        $this->addPlugin('Authentication');
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $csrf = new CsrfProtectionMiddleware([
            'httponly' => true,
        ]);

        $csrf->skipCheckCallback(function ($request) {
            $path = ltrim($request->getPath(), '/');

            return strncmp($path, 'api', 3) === 0;
        });

        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))
            ->add(new BodyParserMiddleware())
            ->add(new AuthenticationMiddleware($this))

            ->add($csrf);

        return $middlewareQueue;
    }

    /**
     * Provide the AuthenticationService instance required by AuthenticationMiddleware.
     */
    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        $fields = [
            'username' => 'username',
            'password' => 'password',
        ];

        $service->loadIdentifier('Authentication.Password', [
            'fields' => $fields,
        ]);

        $service->loadAuthenticator('Authentication.Session');

        // ✅ Fix: make loginUrl dynamic so it works in subfolder installs like /ricemillsys/
        $loginUrl = $request->getAttribute('webroot') . 'users/login';
    }
}