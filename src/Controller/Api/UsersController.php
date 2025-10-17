<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Model\Entity\User;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Datasource\Exception\RecordNotFoundException;

class UsersController extends AppController
{
    protected function getUnauthenticatedActions(): array
    {
        return ['login', 'csrf'];
    }

    /**
     * Provide a CSRF token for Flutter clients that expect to fetch it explicitly.
     */
    public function csrf(): void
    {
        $this->request->allowMethod(['get']);

        $token = (string)($this->request->getAttribute('csrfToken') ?? '');

        if ($token === '') {
            $token = (string)$this->request->getParam('_csrfToken', '');
        }

        $this->respondSuccess([
            'csrfToken' => $token,
            'token' => $token,
        ]);
    }

    /**
     * Authenticate a user and persist the session for subsequent API calls.
     */
    public function login(): void
    {
        $this->request->allowMethod(['post']);

        $data = $this->getRequestPayload();
        $username = isset($data['username']) ? trim((string)$data['username']) : '';
        $password = isset($data['password']) ? (string)$data['password'] : '';

        if ($username === '' || $password === '') {
            $this->respondError('Username and password are required.', 422);
            return;
        }

        $user = $this->Users->find()
            ->where(['username' => $username])
            ->first();

        if (!$user) {
            $this->respondError('Invalid username or password.', 401);
            return;
        }

        $hasher = new DefaultPasswordHasher();
        if (!$hasher->check($password, (string)$user->password)) {
            $this->respondError('Invalid username or password.', 401);
            return;
        }

        if ($this->components()->has('Authentication')) {
            $this->Authentication->setIdentity($user);
            if (method_exists($this->Authentication, 'regenerateSessionId')) {
                $this->Authentication->regenerateSessionId();
            }
        }

        $this->respondSuccess([
            'user' => $this->serializeUser($user),
        ]);
    }

    /**
     * Destroy the current session.
     */
    public function logout(): void
    {
        $this->request->allowMethod(['post']);

        if ($this->components()->has('Authentication')) {
            $this->Authentication->logout();
        }

        $this->respondSuccess();
    }

    /**
     * List users visible to the authenticated identity.
     */
    public function index(): void
    {
        $this->request->allowMethod(['get']);

        $currentUser = $this->requireAuthenticatedUser();
        if (!$currentUser) {
            return;
        }

        $role = strtolower((string)$currentUser->role);
        if (!in_array($role, ['admin', 'owner', 'staff'], true)) {
            $this->respondError('You are not authorized to view users.', 403);
            return;
        }

        $query = $this->Users->find()
            ->orderAsc('Users.id');

        if ($role === 'staff') {
            $query->where(['role' => 'customer']);
        } else {
            $query->where(['role IN' => ['admin', 'owner', 'staff']]);
        }

        $users = [];
        foreach ($query as $user) {
            $users[] = $this->serializeUser($user);
        }

        $this->respondSuccess(['users' => $users]);
    }

    /**
     * Create a new user record.
     */
    public function add(): void
    {
        $this->request->allowMethod(['post']);

        $currentUser = $this->requireAuthenticatedUser();
        if (!$currentUser) {
            return;
        }

        $role = strtolower((string)$currentUser->role);
        if (!in_array($role, ['admin', 'owner', 'staff'], true)) {
            $this->respondError('You are not authorized to create users.', 403);
            return;
        }

        $data = $this->getRequestPayload();
        if ($role === 'staff') {
            $data['role'] = 'customer';
        } elseif (empty($data['role'])) {
            $data['role'] = 'customer';
        }

        $user = $this->Users->newEmptyEntity();
        $user = $this->Users->patchEntity($user, $data);

        if ($user->hasErrors()) {
            $this->respondError('Unable to create user.', 422, [
                'validationErrors' => $user->getErrors(),
            ]);
            return;
        }

        if (!$this->Users->save($user)) {
            $this->respondError('Unable to save user. Please try again.', 500);
            return;
        }

        $this->respondSuccess([
            'user' => $this->serializeUser($user),
        ], 201);
    }

    /**
     * Update an existing user record.
     */
    public function edit($id): void
    {
        $this->request->allowMethod(['put', 'patch']);

        $currentUser = $this->requireAuthenticatedUser();
        if (!$currentUser) {
            return;
        }

        $role = strtolower((string)$currentUser->role);
        if (!in_array($role, ['admin', 'owner', 'staff'], true)) {
            $this->respondError('You are not authorized to update users.', 403);
            return;
        }

        try {
            $user = $this->Users->get((int)$id);
        } catch (RecordNotFoundException $exception) {
            $this->respondError('User not found.', 404);
            return;
        }

        if ($role === 'staff' && strtolower((string)$user->role) !== 'customer') {
            $this->respondError('Staff may only update customer accounts.', 403);
            return;
        }

        $data = $this->getRequestPayload();
        if (array_key_exists('password', $data) && trim((string)$data['password']) === '') {
            unset($data['password']);
        }

        if ($role === 'staff') {
            $data['role'] = 'customer';
        }

        $user = $this->Users->patchEntity($user, $data);

        if ($user->hasErrors()) {
            $this->respondError('Unable to update user.', 422, [
                'validationErrors' => $user->getErrors(),
            ]);
            return;
        }

        if (!$this->Users->save($user)) {
            $this->respondError('Unable to save user. Please try again.', 500);
            return;
        }

        $this->respondSuccess([
            'user' => $this->serializeUser($user),
        ]);
    }

    /**
     * Delete a user record.
     */
    public function delete($id): void
    {
        $this->request->allowMethod(['delete']);

        $currentUser = $this->requireAuthenticatedUser();
        if (!$currentUser) {
            return;
        }

        $role = strtolower((string)$currentUser->role);
        if (!in_array($role, ['admin', 'owner', 'staff'], true)) {
            $this->respondError('You are not authorized to delete users.', 403);
            return;
        }

        try {
            $user = $this->Users->get((int)$id);
        } catch (RecordNotFoundException $exception) {
            $this->respondError('User not found.', 404);
            return;
        }

        if ((int)$currentUser->id === (int)$user->id) {
            $this->respondError('You cannot delete your own account.', 400);
            return;
        }

        if ($role === 'staff' && strtolower((string)$user->role) !== 'customer') {
            $this->respondError('Staff may only delete customer accounts.', 403);
            return;
        }

        if (!$this->Users->delete($user)) {
            $this->respondError('Unable to delete user. Please try again.', 500);
            return;
        }

        $this->respondSuccess();
    }

    /**
     * Return the authenticated user's profile data.
     */
    public function profile(): void
    {
        $this->request->allowMethod(['get']);

        $user = $this->requireAuthenticatedUser();
        if (!$user) {
            return;
        }

        $this->respondSuccess([
            'user' => $this->serializeUser($user),
        ]);
    }

    /**
     * List all customers (non staff/admin) for staff dashboards.
     */
    public function customers(): void
    {
        $this->request->allowMethod(['get']);

        $currentUser = $this->requireAuthenticatedUser();
        if (!$currentUser) {
            return;
        }

        $role = strtolower((string)$currentUser->role);
        if (!in_array($role, ['admin', 'owner', 'staff'], true)) {
            $this->respondError('You are not authorized to view customers.', 403);
            return;
        }

        $query = $this->Users->find()
            ->where(['role' => 'customer'])
            ->orderAsc('Users.id');

        $customers = [];
        foreach ($query as $customer) {
            $customers[] = $this->serializeUser($customer);
        }

        $this->respondSuccess(['customers' => $customers]);
    }
}