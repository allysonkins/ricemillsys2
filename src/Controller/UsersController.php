<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\AppController;

class UsersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Flash');

        // Allow guests to access login only
        $this->Authentication->addUnauthenticatedActions(['login', 'redirectHome']);
    }

    /**
     * Redirect users to their appropriate dashboard
     */
    public function redirectHome()
    {
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $identity = $this->request->getAttribute('identity');
            $role = strtolower(trim($identity->role ?? ''));

            if ($role === 'customer') {
                return $this->redirect(['controller' => 'MillingOrders', 'action' => 'index']);
            }

            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        return $this->redirect(['action' => 'login']);
    }

    /**
     * Login method
     */
    public function login()
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        if ($result && $result->isValid()) {
            $identity = $this->request->getAttribute('identity');
            $role = strtolower(trim($identity->role ?? ''));

            if ($role === 'customer') {
                return $this->redirect(['controller' => 'MillingOrders', 'action' => 'index']);
            }

            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        if ($this->request->is('post')) {
            if (!$result->isValid()) {
                $this->Flash->error(__('Invalid username or password.'));
            }
        }
    }

    /**
     * Logout method
     */
    public function logout()
    {
        $this->Authentication->logout();
        $this->Flash->success(__('You have been logged out.'));
        return $this->redirect(['action' => 'login']);
    }

    /**
     * Add User method (admin and staff only)
     */
    public function add()
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to add users.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $currentUserRole = strtolower(trim($identity->role ?? ''));

        // Only admin, owner, and staff can add users
        if (!in_array($currentUserRole, ['admin', 'owner', 'staff'])) {
            $this->Flash->error(__('You are not authorized to add users.'));
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }

        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $userData = $this->request->getData();
            
            // Set default role based on current user's permissions
            if ($currentUserRole === 'staff') {
                // Staff can only create customers
                $userData['role'] = 'customer';
            } elseif (empty($userData['role'])) {
                // Default role for admin/owner if not specified
                $userData['role'] = 'customer';
            }

            // Ensure required fields are set
            if (empty($userData['username'])) {
                $this->Flash->error(__('Username is required.'));
                $this->set(compact('user'));
                return;
            }

            if (empty($userData['password'])) {
                $this->Flash->error(__('Password is required.'));
                $this->set(compact('user'));
                return;
            }

            $user = $this->Users->patchEntity($user, $userData);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('User has been created successfully.'));
                
                // Redirect to appropriate list based on role
                if ($userData['role'] === 'customer') {
                    return $this->redirect(['action' => 'customers']);
                } else {
                    return $this->redirect(['action' => 'index']);
                }
            } else {
                $errorMessage = __('Unable to create user. Please check all required fields and try again.');
                $errors = $user->getErrors();
                if (!empty($errors)) {
                    $firstFieldErrors = current($errors);
                    if (is_array($firstFieldErrors) && !empty($firstFieldErrors)) {
                        $firstError = current($firstFieldErrors);
                        if (is_string($firstError)) {
                            $errorMessage = __($firstError);
                        }
                    }
                }
                $this->Flash->error($errorMessage);
            }
        }

        $roles = [];
        if (in_array($currentUserRole, ['admin', 'owner'])) {
            $roles = [
                'admin' => 'Admin', 
                'staff' => 'Staff',
                
                
            ];
        } elseif ($currentUserRole === 'staff') {
            $roles = ['customer' => 'Customer'];
        }

        $this->set(compact('user', 'roles', 'currentUserRole'));
    }

    /**
     * View all users (admin/owner/staff only)
     */
    public function index()
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to view this page.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        if (in_array($role, ['admin', 'owner', 'staff'])) {
            // Only show admin, staff, and owner users (not customers)
            $query = $this->Users->find()
                ->where(['role IN' => ['admin', 'staff', 'owner']])
                ->order(['Users.id' => 'ASC']);

            $this->paginate = [
                'limit' => 10,
            ];
            $users = $this->paginate($query);
            $this->set(compact('users', 'role'));
        } else {
            $this->Flash->error(__('You are not authorized to view this page.'));
            return $this->redirect(['action' => 'profile']);
        }
    }

    /**
     * View Customers (admin/owner/staff only)
     */
    public function customers()
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to view this page.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        if (in_array($role, ['admin', 'owner', 'staff'])) {
            
            // Only show customers
            $query = $this->Users->find()
                ->where(['role' => 'customer'])
                ->order(['Users.created' => 'DESC']);

            $this->paginate = [
                'limit' => 10,
            ];
            $customers = $this->paginate($query);
            $this->set(compact('customers', 'role'));
        } else {
            $this->Flash->error(__('You are not authorized to view this page.'));
            return $this->redirect(['action' => 'profile']);
        }
    }

    /**
     * View single user
     */
    public function view($id = null)
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to view this page.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        if (in_array($role, ['admin', 'owner', 'staff']) || (int)$identity->id === (int)$id) {
            $user = $this->Users->get($id);
            $this->set(compact('user', 'role'));
        } else {
            $this->Flash->error(__('You are not authorized to view this user.'));
            return $this->redirect(['action' => 'profile']);
        }
    }

    /**
     * View Customer (admin/owner/staff only)
     */
    public function viewCustomer($id = null)
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to view this page.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        if (in_array($role, ['admin', 'owner', 'staff'])) {
            $customer = $this->Users->get($id);
            
            // Verify this is actually a customer
            if ($customer->role !== 'customer') {
                $this->Flash->error(__('The specified user is not a customer.'));
                return $this->redirect(['action' => 'customers']);
            }
            
            $this->set(compact('customer', 'role'));
        } else {
            $this->Flash->error(__('You are not authorized to view this page.'));
            return $this->redirect(['action' => 'profile']);
        }
    }

    /**
     * Profile (view only for current user)
     */
    public function profile()
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to view your profile.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $user = $this->Users->get($identity->id);
        $this->set(compact('user'));
    }

    /**
     * Edit user - Complete details editing
     */
    public function edit($id = null)
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to edit your profile.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $currentUserRole = strtolower(trim($identity->role ?? ''));
        
        $user = $this->Users->get($id);

        // Check authorization - allow users to edit their own profile
        if ((int)$identity->id !== (int)$id) {
            // If editing another user, check permissions
            if (!in_array($currentUserRole, ['admin', 'owner', 'staff'])) {
                $this->Flash->error(__('You are not authorized to edit other users.'));
                return $this->redirect(['action' => 'profile']);
            }
            
            // Staff can only edit customer users (not other staff, admin, or owner)
            if ($currentUserRole === 'staff' && $user->role !== 'customer') {
                $this->Flash->error(__('You are not authorized to edit this user. Staff can only edit customer accounts.'));
                return $this->redirect(['action' => 'customers']);
            }
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $userData = $this->request->getData();
            
            // Role restrictions based on current user's role
            if ($currentUserRole === 'staff') {
                // Staff cannot change roles
                unset($userData['role']);
            } elseif (!in_array($currentUserRole, ['admin', 'owner']) && (int)$identity->id !== (int)$id) {
                // Regular users cannot change other users' roles
                unset($userData['role']);
            }

            $user = $this->Users->patchEntity($user, $userData);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Profile updated successfully.'));
                
                // Redirect based on who is editing
                if ((int)$identity->id === (int)$id) {
                    return $this->redirect(['action' => 'profile']);
                } else {
                    if ($user->role === 'customer') {
                        return $this->redirect(['action' => 'customers']);
                    } else {
                        return $this->redirect(['action' => 'index']);
                    }
                }
            } else {
                $errorMessage = __('Unable to update profile. Please try again.');
                $errors = $user->getErrors();
                if (!empty($errors)) {
                    $firstFieldErrors = current($errors);
                    if (is_array($firstFieldErrors) && !empty($firstFieldErrors)) {
                        $firstError = current($firstFieldErrors);
                        if (is_string($firstError)) {
                            $errorMessage = __($firstError);
                        }
                    }
                }
                $this->Flash->error($errorMessage);
            }
        }

        $roles = [];
        if (in_array($currentUserRole, ['admin', 'owner'])) {
            $roles = [
                'admin' => 'Admin', 
                'staff' => 'Staff', 
                'customer' => 'Customer',
                'owner' => 'Owner'
            ];
        }

        $this->set(compact('user', 'roles', 'currentUserRole'));
    }

    /**
     * Edit Customer (admin/owner/staff only)
     */
    public function editCustomer($id = null)
    {
        $result = $this->Authentication->getResult();

        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to edit customers.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $currentUserRole = strtolower(trim($identity->role ?? ''));

        if (!in_array($currentUserRole, ['admin', 'owner', 'staff'])) {
            $this->Flash->error(__('You are not authorized to edit customers.'));
            return $this->redirect(['action' => 'profile']);
        }

        $customer = $this->Users->get($id);
        
        // Verify this is actually a customer
        if ($customer->role !== 'customer') {
            $this->Flash->error(__('The specified user is not a customer.'));
            return $this->redirect(['action' => 'customers']);
        }

        if ($this->request->is(['post', 'put', 'patch'])) {
            $customerData = $this->request->getData();
            
            // Staff cannot change roles
            if ($currentUserRole === 'staff') {
                unset($customerData['role']);
            }

            $customer = $this->Users->patchEntity($customer, $customerData);

            if ($this->Users->save($customer)) {
                $this->Flash->success(__('Customer updated successfully.'));
                return $this->redirect(['action' => 'viewCustomer', $customer->id]);
            } else {
                $errorMessage = __('Unable to update customer. Please try again.');
                $errors = $customer->getErrors();
                if (!empty($errors)) {
                    $firstFieldErrors = current($errors);
                    if (is_array($firstFieldErrors) && !empty($firstFieldErrors)) {
                        $firstError = current($firstFieldErrors);
                        if (is_string($firstError)) {
                            $errorMessage = __($firstError);
                        }
                    }
                }
                $this->Flash->error($errorMessage);
            }
        }

        $this->set(compact('customer', 'currentUserRole'));
    }

    /**
     * Delete user (admin/owner only)
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to delete users.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        if (!in_array($role, ['admin', 'owner'])) {
            $this->Flash->error(__('You are not authorized to delete users.'));
            return $this->redirect(['action' => 'index']);
        }

        // Prevent users from deleting themselves
        if ((int)$identity->id === (int)$id) {
            $this->Flash->error(__('You cannot delete your own account.'));
            return $this->redirect(['action' => 'index']);
        }

        $user = $this->Users->get($id);

        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Delete Customer (admin/owner only)
     */
    public function deleteCustomer($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to delete customers.'));
            return $this->redirect(['action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        if (!in_array($role, ['admin', 'owner'])) {
            $this->Flash->error(__('You are not authorized to delete customers.'));
            return $this->redirect(['action' => 'customers']);
        }

        $customer = $this->Users->get($id);
        
        // Verify this is actually a customer
        if ($customer->role !== 'customer') {
            $this->Flash->error(__('The specified user is not a customer.'));
            return $this->redirect(['action' => 'customers']);
        }

        if ($this->Users->delete($customer)) {
            $this->Flash->success(__('The customer has been deleted.'));
        } else {
            $this->Flash->error(__('The customer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'customers']);
    }

    public function addcustomers()
{
    $result = $this->Authentication->getResult();

    // ✅ Ensure user is logged in
    if (!$result || !$result->isValid()) {
        $this->Flash->error(__('You must be logged in to access this page.'));
        return $this->redirect(['action' => 'login']);
    }

    $identity = $this->request->getAttribute('identity');
    $currentUserRole = strtolower(trim($identity->role ?? ''));

    // ✅ Only allow STAFF to access this method
    if ($currentUserRole !== 'staff') {
        $this->Flash->error(__('Only staff can add customer accounts.'));
        return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
    }

    $user = $this->Users->newEmptyEntity();

    if ($this->request->is('post')) {

        // ✅ Handle cancel button
        if ($this->request->getData('cancel')) {
            return $this->redirect(['action' => 'customers']);
        }

        $userData = $this->request->getData();
        $userData['role'] = 'customer'; // ✅ Force role

        $user = $this->Users->patchEntity($user, $userData);

        if ($this->Users->save($user)) {
            $this->Flash->success(__('Customer has been created successfully.'));
            return $this->redirect(['action' => 'customers']);
        } else {
            $this->Flash->error(__('Failed to create customer.'));
        }
    }

    // ✅ No roles selection needed — role is fixed to customer
    $roles = null;

    $this->set(compact('user', 'roles', 'currentUserRole'));
}


}