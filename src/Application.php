<?php
use Cake\Routing\Middleware\RoutingMiddleware;

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
            $path = $request->getPath();

            return strpos($path, '/api/') === 0 || strpos($path, 'api/') === 0;
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