<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Authentication\PasswordHasher\DefaultPasswordHasher;

class UsersController extends AppController
{
    /**
     * API login endpoint for Flutter clients.
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

        $token = $this->generateToken((int)$user->id);

        $this->respondSuccess([
            'token' => $token,
            'user' => [
                'id' => (int)$user->id,
                'username' => $user->username,
                'role' => $user->role,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ],
        ]);
    }

    /**
     * Return details for the authenticated user.
     */
    public function profile(): void
    {
        $this->request->allowMethod(['get']);

        $user = $this->requireAuthenticatedUser();
        if (!$user) {
            return;
        }

        $this->respondSuccess([
            'user' => [
                'id' => (int)$user->id,
                'username' => $user->username,
                'role' => $user->role,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
            ],
        ]);
    }
}