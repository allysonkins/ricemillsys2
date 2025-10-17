<?php
declare(strict_types=1);

namespace App\Controller;

class MillingOrdersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Flash');
        
        $this->MillingOrders = $this->fetchTable('MillingOrders');
        $this->Users = $this->fetchTable('Users');
        $this->Payments = $this->fetchTable('Payments');
    }

    public function index()
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('Please log in to access milling orders.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        // Build query based on role
        $query = $this->MillingOrders->find()
            ->contain(['Users', 'Payments'])
            ->order(['MillingOrders.id' => 'DESC']);

        // Customers can only see their own orders
        if ($role === 'customer') {
            $userId = $identity->id ?? null;
            if ($userId) {
                $query->where(['MillingOrders.user_id' => $userId]);
            } else {
                $this->Flash->error(__('No customer profile associated with your account. Please contact administrator.'));
                $orders = [];
                $this->set(compact('orders', 'role'));
                return;
            }
        }

        $orders = $this->paginate($query);
        $this->set(compact('orders', 'role'));
    }

    public function add()
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to add milling orders.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        // Only staff, admin, owner can add orders (not customers)
        if ($role === 'customer') {
            $this->Flash->error(__('You are not authorized to add milling orders.'));
            return $this->redirect(['action' => 'index']);
        }

        $order = $this->MillingOrders->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            
            // Set default values for required fields
            $data['status'] = 'Pending';
            $data['delivery_status'] = 'pending';
            $data['payment_status'] = 'pending';
            
            // Ensure numeric fields are properly cast
            if (isset($data['weight'])) {
                $data['weight'] = (float)$data['weight'];
            }
            if (isset($data['total_amount'])) {
                $data['total_amount'] = (float)$data['total_amount'];
            }
            
            $order = $this->MillingOrders->patchEntity($order, $data);
        
            if ($this->MillingOrders->save($order)) {
                $this->Flash->success(__('The milling order has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            
            // Show detailed error messages without debug()
            $errors = $order->getErrors();
            if (!empty($errors)) {
                foreach ($errors as $field => $errorMessages) {
                    foreach ($errorMessages as $errorMessage) {
                        $this->Flash->error(__($field . ': ' . $errorMessage));
                    }
                }
            } else {
                $this->Flash->error(__('The milling order could not be saved. Please check your input and try again.'));
            }
        }

        // Get only customer users
        $customers = $this->Users->find('list')
            ->where(['role' => 'customer'])
            ->all();

        $deliveryOptions = [
            'pickup' => 'Customer Pickup',
            'delivery' => 'Home Delivery'
        ];

        $this->set(compact('order', 'customers', 'deliveryOptions', 'role'));
    }

    public function view($id = null)
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('Please log in to view milling orders.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        $order = $this->MillingOrders->get($id, [
            'contain' => ['Users', 'Payments']
        ]);

        // Check authorization - customers can only view their own orders
        if ($role === 'customer') {
            $userId = $identity->id ?? null;
            if ($order->user_id !== $userId) {
                $this->Flash->error(__('You are not authorized to view this order.'));
                return $this->redirect(['action' => 'index']);
            }
        }

        // Calculate total paid
        $totalPaid = 0;
        if (!empty($order->payments)) {
            foreach ($order->payments as $payment) {
                $totalPaid += (float)$payment->amount;
            }
        }

        $this->set(compact('order', 'totalPaid', 'role'));
    }

    public function edit($id = null)
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to edit milling orders.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        // Only staff, admin, owner can edit orders (not customers)
        if ($role === 'customer') {
            $this->Flash->error(__('You are not authorized to edit milling orders.'));
            return $this->redirect(['action' => 'index']);
        }

        // Include Users in the contain to avoid null user error
        $order = $this->MillingOrders->get($id, [
            'contain' => ['Users', 'Payments']
        ]);

        // Calculate total paid for display
        $totalPaid = 0;
        if (!empty($order->payments)) {
            foreach ($order->payments as $payment) {
                $totalPaid += (float)$payment->amount;
            }
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $order = $this->MillingOrders->patchEntity($order, $this->request->getData());
            if ($this->MillingOrders->save($order)) {
                $this->Flash->success(__('The milling order has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The milling order could not be saved. Please, try again.'));
        }
        
        // Get only customer users
        $customers = $this->Users->find('list')
            ->where(['role' => 'customer'])
            ->all();
        
        $deliveryOptions = [
            'pickup' => 'Customer Pickup',
            'delivery' => 'Home Delivery'
        ];
        
        $statusOptions = [
            'Pending' => 'Pending',
            'Milling' => 'Milling', 
            'Completed' => 'Completed'
        ];
        
        $deliveryStatusOptions = [
            'pending' => 'Pending',
            'ready_for_pickup' => 'Ready for Pickup',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'picked_up' => 'Picked Up'
        ];
        
        $this->set(compact('order', 'customers', 'deliveryOptions', 'statusOptions', 'deliveryStatusOptions', 'totalPaid', 'role'));
    }

    public function quickUpdate($id = null)
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to update orders.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        // Only staff, admin, owner can update orders (not customers)
        if ($role === 'customer') {
            $this->Flash->error(__('You are not authorized to update orders.'));
            return $this->redirect(['action' => 'index']);
        }

        // Load the Users association
        $order = $this->MillingOrders->get($id, [
            'contain' => ['Users']
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $order = $this->MillingOrders->patchEntity($order, $this->request->getData());
            if ($this->MillingOrders->save($order)) {
                $this->Flash->success(__('The order status has been updated.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The order status could not be updated. Please, try again.'));
        }
        
        $statusOptions = [
            'Pending' => 'Pending',
            'Milling' => 'Milling', 
            'Completed' => 'Completed'
        ];
        
        $deliveryStatusOptions = [
            'pending' => 'Pending',
            'ready_for_pickup' => 'Ready for Pickup',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered',
            'picked_up' => 'Picked Up'
        ];
        
        $paymentStatusOptions = [
            'pending' => 'Pending',
            'partial' => 'Partial',
            'paid' => 'Paid'
        ];
        
        $this->set(compact('order', 'statusOptions', 'deliveryStatusOptions', 'paymentStatusOptions', 'role'));
    }

    public function delete($id = null)
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to delete orders.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? ''));

        // Only staff, admin, owner can delete orders (not customers)
        if ($role === 'customer') {
            $this->Flash->error(__('You are not authorized to delete orders.'));
            return $this->redirect(['action' => 'index']);
        }

        $this->request->allowMethod(['post', 'delete']);
        $order = $this->MillingOrders->get($id);
        if ($this->MillingOrders->delete($order)) {
            $this->Flash->success(__('The milling order has been deleted.'));
        } else {
            $this->Flash->error(__('The milling order could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}