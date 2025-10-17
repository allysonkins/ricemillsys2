<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController as BaseAppController;
use App\Model\Entity\User;
use Authentication\IdentityInterface;
use Cake\Event\EventInterface;

/**
 * Base controller for JSON API endpoints.
 */
class AppController extends BaseAppController
{
    /**
     * Cached authenticated user for the current request.
     */
    protected ?User $authenticatedUser = null;

    public function initialize(): void
    {
        parent::initialize();

        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', false)
            ->setLayout(false);

        $this->RequestHandler->renderAs($this, 'json');

        if ($this->components()->has('Flash')) {
            $this->components()->unload('Flash');
        }

        $this->loadModel('Users');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->RequestHandler->renderAs($this, 'json');

        if ($this->components()->has('Authentication')) {
            $this->Authentication->addUnauthenticatedActions($this->getUnauthenticatedActions());
        }
    }

    /**
     * Override to allow unauthenticated access for specific actions.
     */
    protected function getUnauthenticatedActions(): array
    {
        return [];
    }

    /**
     * Convenience method to build a successful JSON response.
     */
    protected function respondSuccess(array $data = [], int $status = 200): void
    {
        $this->response = $this->response->withStatus($status);
        $payload = ['success' => true] + $data;
        $this->set($payload);
        $this->viewBuilder()->setOption('serialize', array_keys($payload));
    }

    /**
     * Convenience method to build an error JSON response.
     */
    protected function respondError(string $message, int $status = 400, array $extra = []): void
    {
        $this->response = $this->response->withStatus($status);
        $payload = ['success' => false, 'error' => $message] + $extra;
        $this->set($payload);
        $this->viewBuilder()->setOption('serialize', array_keys($payload));
    }

    /**
     * Normalize incoming request payloads.
     */
    protected function getRequestPayload(): array
    {
        $data = $this->request->getData();
        if (!empty($data)) {
            return is_array($data) ? $data : (array)$data;
        }

        $json = $this->request->input('json_decode', true);
        return is_array($json) ? $json : [];
    }

    /**
     * Retrieve the Authentication identity when available.
     */
    protected function getIdentity(): ?IdentityInterface
    {
        if (!$this->components()->has('Authentication')) {
            return null;
        }

        return $this->Authentication->getIdentity();
    }

    /**
     * Ensure the current request has an authenticated user.
     */
    protected function requireAuthenticatedUser(): ?User
    {
        if ($this->authenticatedUser instanceof User) {
            return $this->authenticatedUser;
        }

        $identity = $this->getIdentity();
        if (!$identity) {
            $this->respondError('Authentication is required.', 401);
            return null;
        }

        $user = null;
        if (method_exists($identity, 'getOriginalData')) {
            $original = $identity->getOriginalData();
            if ($original instanceof User) {
                $user = $original;
            }
        }

        if (!$user) {
            $userId = null;
            if (method_exists($identity, 'getIdentifier')) {
                $userId = $identity->getIdentifier();
            } elseif (is_array($identity) && isset($identity['id'])) {
                $userId = $identity['id'];
            }

            if ($userId === null) {
                $this->respondError('Unable to resolve authenticated user.', 401);
                return null;
            }

            $user = $this->Users->find()
                ->where(['Users.id' => (int)$userId])
                ->first();

            if (!$user) {
                $this->respondError('Authenticated user record no longer exists.', 401);
                return null;
            }
        }

        $this->authenticatedUser = $user;
        return $this->authenticatedUser;
    }

    /**
     * Serialize a user entity for API responses.
     */
    protected function serializeUser(User $user): array
    {
        return [
            'id' => (int)$user->id,
            'username' => $user->username,
            'role' => $user->role,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
        ];
    }
}