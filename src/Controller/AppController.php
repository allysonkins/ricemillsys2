<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

/**
 * Application Controller
 *
 * Base controller for all other controllers.
 */
class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        // ✅ Load essential CakePHP components
        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Flash');

        // ✅ Enable Authentication globally (only load once)
        if (!$this->components()->has('Authentication')) {
            $this->loadComponent('Authentication.Authentication');
        }

        // ❌ REMOVE this line:
        // $this->loadComponent('Paginator');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // ✅ Allow unauthenticated access for login and register
        $this->Authentication->addUnauthenticatedActions(['login', 'register']);

        // ✅ Enable JSON view automatically for API requests
        if ($this->request->is('json') || $this->request->accepts('application/json')) {
            $this->viewBuilder()->setClassName('Json');
        }
    }

    /**
     * Helper: Get current logged-in user
     */
    protected function getIdentity()
    {
        return $this->request->getAttribute('identity');
    }

    /**
     * Helper: Get current user's role
     */
    protected function getRole(): string
    {
        $identity = $this->getIdentity();
        return strtolower(trim($identity->role ?? 'guest'));
    }
}
