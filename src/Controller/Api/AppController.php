<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController as BaseAppController;
use App\Model\Entity\User;
use Cake\Event\EventInterface;
use Cake\Utility\Security;

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

        // API responses are always JSON with no layout.
        $this->viewBuilder()
            ->setClassName('Json')
            ->setOption('serialize', false)
            ->setLayout(false);

        $this->RequestHandler->renderAs($this, 'json');

        // API endpoints should not use Flash messages or session based authentication.
        if ($this->components()->has('Flash')) {
            $this->components()->unload('Flash');
        }

        if ($this->components()->has('Authentication')) {
            $this->components()->unload('Authentication');
        }

        $this->loadModel('Users');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // Ensure JSON responses even when the Accept header is missing.
        $this->RequestHandler->renderAs($this, 'json');
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
     * Generate a signed token for stateless API authentication.
     */
    protected function generateToken(int $userId, int $ttlSeconds = 86400): string
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + $ttlSeconds;
        $payload = $userId . '|' . $expiresAt;
        $signature = hash_hmac('sha256', $payload, Security::getSalt());

        return base64_encode($payload . '|' . $signature);
    }

    /**
     * Decode and validate a previously generated token.
     */
    protected function decodeToken(?string $token): ?array
    {
        if (!$token) {
            return null;
        }

        $decoded = base64_decode($token, true);
        if ($decoded === false) {
            return null;
        }

        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            return null;
        }

        [$userId, $expiresAt, $signature] = $parts;
        if (!ctype_digit((string)$userId) || !ctype_digit((string)$expiresAt)) {
            return null;
        }

        if ((int)$expiresAt <= time()) {
            return null;
        }

        $payload = $userId . '|' . $expiresAt;
        $expectedSignature = hash_hmac('sha256', $payload, Security::getSalt());

        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        return [
            'user_id' => (int)$userId,
            'expires_at' => (int)$expiresAt,
        ];
    }

    /**
     * Extract a bearer token from the Authorization header or request parameters.
     */
    protected function getTokenFromRequest(): ?string
    {
        $authorization = $this->request->getHeaderLine('Authorization');
        if ($authorization && preg_match('/Bearer\s+(.*)/i', $authorization, $matches)) {
            return trim($matches[1]);
        }

        $token = $this->request->getQuery('token');
        if (is_string($token) && $token !== '') {
            return $token;
        }

        $token = $this->request->getData('token');
        if (is_string($token) && $token !== '') {
            return $token;
        }

        return null;
    }

    /**
     * Ensure the request has a valid authenticated user.
     */
    protected function requireAuthenticatedUser(): ?User
    {
        if ($this->authenticatedUser) {
            return $this->authenticatedUser;
        }

        $token = $this->getTokenFromRequest();
        if (!$token) {
            $this->respondError('Authentication token is required.', 401);
            return null;
        }

        $payload = $this->decodeToken($token);
        if (!$payload) {
            $this->respondError('Invalid or expired token.', 401);
            return null;
        }

        $user = $this->Users->find()
            ->where(['Users.id' => $payload['user_id']])
            ->first();

        if (!$user) {
            $this->respondError('User not found for provided token.', 401);
            return null;
        }

        $this->authenticatedUser = $user;
        return $this->authenticatedUser;
    }
}