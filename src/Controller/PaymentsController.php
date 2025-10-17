<?php
declare(strict_types=1);

namespace App\Controller;

class PaymentsController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadComponent('Authentication.Authentication');
        
        $this->Payments = $this->fetchTable('Payments');
        $this->MillingOrders = $this->fetchTable('MillingOrders');
        $this->Users = $this->fetchTable('Users'); // Changed from Customers to Users
    }

    public function index()
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('Please log in to access payments.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        // Load payments with their relationships
        $payments = $this->Payments->find()
            ->contain([
                'Users', // Changed from Customers to Users
                'MillingOrders' => function ($q) {
                    return $q->find('all');
                }
            ])
            ->order(['Payments.created' => 'DESC']);

        $this->set(compact('payments'));
    }

    public function view($id = null)
    {
        $payment = $this->Payments->get($id, [
            'contain' => ['Users', 'MillingOrders'] // Changed from Customers to Users
        ]);

        $this->set(compact('payment'));
    }

    public function add()
    {
        $payment = $this->Payments->newEmptyEntity();
        
        // Get milling_order_id from query string if available
        $millingOrderId = $this->request->getQuery('milling_order_id');
        
        // Variables to hold order details for display
        $orderTotal = 0;
        $totalPaid = 0;
        $balance = 0;
        $millingOrder = null;
        
        // If we have a milling order ID, get the order and pre-populate
        if (!empty($millingOrderId)) {
            try {
                $millingOrder = $this->MillingOrders->get($millingOrderId, [
                    'contain' => ['Users', 'Payments'] // Changed from Customers to Users
                ]);
                
                // Calculate total paid and balance
                $orderTotal = (float)$millingOrder->total_amount;
                foreach ($millingOrder->payments as $existingPayment) {
                    $totalPaid += (float)$existingPayment->amount;
                }
                $balance = $orderTotal - $totalPaid;
                
                // Pre-populate the payment entity
                $payment->user_id = $millingOrder->user_id; // Changed from customer_id to user_id
                $payment->milling_order_id = $millingOrderId;
                
                // Also set the user association so it's available in the view
                $payment->user = $millingOrder->user; // Changed from customer to user
                
            } catch (\Exception $e) {
                $this->Flash->error(__('Invalid milling order selected.'));
                \Cake\Log\Log::error('Error loading milling order: ' . $e->getMessage());
            }
        }
        
        if ($this->request->is('post')) {
            $paymentData = $this->request->getData();
            
            // Convert empty milling_order_id to null
            if (isset($paymentData['milling_order_id']) && $paymentData['milling_order_id'] === '') {
                $paymentData['milling_order_id'] = null;
            }
            
            // Ensure payment_date is set
            if (empty($paymentData['payment_date'])) {
                $paymentData['payment_date'] = date('Y-m-d H:i:s');
            } else {
                $paymentData['payment_date'] = date('Y-m-d H:i:s', strtotime($paymentData['payment_date']));
            }
            
            $payment = $this->Payments->patchEntity($payment, $paymentData);
            
            if ($this->Payments->save($payment)) {
                // Update milling order payment status
                if (!empty($payment->milling_order_id)) {
                    $this->updateMillingOrderPaymentStatus($payment->milling_order_id);
                }
                
                $this->Flash->success(__('The payment has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The payment could not be saved. Please, try again.'));
                // Debug: Show validation errors
                $errors = $payment->getErrors();
                if (!empty($errors)) {
                    foreach ($errors as $field => $errorMessages) {
                        foreach ($errorMessages as $errorMessage) {
                            $this->Flash->error(__($field . ': ' . $errorMessage));
                        }
                    }
                }
            }
        }
        
        // Get only customer users
        $customers = $this->Users->find('list')
            ->where(['role' => 'customer'])
            ->all();
        
        $millingOrders = $this->MillingOrders->find('list', [
            'keyField' => 'id',
            'valueField' => function ($order) {
                $totalAmount = (float)($order->total_amount ?? 0);
                $customerName = $order->has('user') ? 
                    ($order->user->first_name . ' ' . $order->user->last_name) : 
                    'Unknown'; // Changed from customer to user
                return "Order #{$order->id} - {$customerName} - ₱" . number_format($totalAmount, 2);
            }
        ])->contain(['Users'])->all(); // Changed from Customers to Users
        
        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check', 
            'transfer' => 'Bank Transfer'
        ];
        
        $this->set(compact('payment', 'customers', 'millingOrders', 'paymentMethods', 'millingOrderId', 'orderTotal', 'totalPaid', 'balance', 'millingOrder'));
    }

    public function edit($id = null)
    {
        $payment = $this->Payments->get($id, [
            'contain' => ['Users'] // Changed from Customers to Users
        ]);
        
        if ($this->request->is(['patch', 'post', 'put'])) {
            $paymentData = $this->request->getData();
            
            // Convert empty milling_order_id to null
            if (isset($paymentData['milling_order_id']) && $paymentData['milling_order_id'] === '') {
                $paymentData['milling_order_id'] = null;
            }
            
            // Ensure payment_date is properly formatted
            if (!empty($paymentData['payment_date'])) {
                $paymentData['payment_date'] = date('Y-m-d H:i:s', strtotime($paymentData['payment_date']));
            }
            
            $payment = $this->Payments->patchEntity($payment, $paymentData);
            
            if ($this->Payments->save($payment)) {
                // Update the milling order payment status only if milling_order_id is set
                if (!empty($payment->milling_order_id)) {
                    $this->updateMillingOrderPaymentStatus($payment->milling_order_id);
                }
                
                $this->Flash->success(__('The payment has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }
        
        // Get only customer users
        $customers = $this->Users->find('list')
            ->where(['role' => 'customer'])
            ->all();
        
        $millingOrders = $this->MillingOrders->find('list', [
            'keyField' => 'id',
            'valueField' => function ($order) {
                $totalAmount = (float)($order->total_amount ?? 0);
                $customerName = $order->has('user') ? 
                    ($order->user->first_name . ' ' . $order->user->last_name) : 
                    'Unknown'; // Changed from customer to user
                return "Order #{$order->id} - {$customerName} - ₱" . number_format($totalAmount, 2);
            }
        ])
        ->contain(['Users']) // Changed from Customers to Users
        ->all();
        
        $paymentMethods = [
            'cash' => 'Cash',
            'check' => 'Check', 
            'transfer' => 'Bank Transfer'
        ];
        
        $this->set(compact('payment', 'customers', 'millingOrders', 'paymentMethods'));
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payment = $this->Payments->get($id);
        
        $millingOrderId = $payment->milling_order_id;
        
        if ($this->Payments->delete($payment)) {
            // Update the milling order payment status only if milling_order_id was set
            if (!empty($millingOrderId)) {
                $this->updateMillingOrderPaymentStatus($millingOrderId);
            }
            
            $this->Flash->success(__('The payment has been deleted.'));
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function updateMillingOrderPaymentStatus($millingOrderId)
    {
        try {
            $millingOrder = $this->MillingOrders->get($millingOrderId, ['contain' => ['Payments']]);
            
            $totalPaid = 0;
            foreach ($millingOrder->payments as $payment) {
                $totalPaid += (float)$payment->amount;
            }
            
            $millingOrderTotal = (float)$millingOrder->total_amount;
            
            if ($totalPaid >= $millingOrderTotal) {
                $millingOrder->payment_status = 'paid';
            } elseif ($totalPaid > 0) {
                $millingOrder->payment_status = 'partial';
            } else {
                $millingOrder->payment_status = 'pending';
            }
            
            $this->MillingOrders->save($millingOrder);
        } catch (\Exception $e) {
            \Cake\Log\Log::error('Error updating payment status: ' . $e->getMessage());
        }
    }

    public function getMillingOrdersByCustomer($userId = null) // Changed parameter name from customerId to userId
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        if (!$userId) {
            echo json_encode([]);
            return;
        }

        $millingOrders = $this->MillingOrders->find('list', [
            'keyField' => 'id',
            'valueField' => function ($order) {
                $totalAmount = (float)($order->total_amount ?? 0);
                return "Order #{$order->id} - ₱" . number_format($totalAmount, 2);
            }
        ])
        ->where(['user_id' => $userId]) // Changed from customer_id to user_id
        ->all();

        $this->response = $this->response->withType('application/json');
        echo json_encode($millingOrders->toArray());
    }

    public function getCustomerByMillingOrder($millingOrderId = null)
    {
        $this->request->allowMethod(['ajax', 'get']);
        $this->autoRender = false;

        if (!$millingOrderId) {
            echo json_encode(['user_id' => null]); // Changed from customer_id to user_id
            return;
        }

        try {
            $millingOrder = $this->MillingOrders->get($millingOrderId);
            $response = ['user_id' => $millingOrder->user_id]; // Changed from customer_id to user_id
        } catch (\Exception $e) {
            $response = ['user_id' => null]; // Changed from customer_id to user_id
        }

        $this->response = $this->response->withType('application/json');
        echo json_encode($response);
    }
}