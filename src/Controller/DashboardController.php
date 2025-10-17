<?php
declare(strict_types=1);

namespace App\Controller;

class DashboardController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        // Load your models - remove Customers, use Users instead
        $this->Users = $this->fetchTable('Users'); // Changed from Customers to Users
        $this->Payments = $this->fetchTable('Payments');
        $this->MillingOrders = $this->fetchTable('MillingOrders');
    }

    public function index()
    {
        $result = $this->Authentication->getResult();
        if (!$result || !$result->isValid()) {
            $this->Flash->error(__('You must be logged in to access the dashboard.'));
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        $identity = $this->request->getAttribute('identity');
        $role = strtolower(trim($identity->role ?? 'staff'));

        if ($role === 'customer') {
            $userId = $identity->id ?? null; // Changed from customer_id to id

            $millingOrdersCount = $this->MillingOrders->find()->where(['user_id' => $userId])->count();
            $customersCount = 1;
            $paymentsCount = $this->Payments->find()->where(['user_id' => $userId])->count(); // Changed from customer_id to user_id
            
            // Calculate customer-specific totals
            $totalPaid = $this->Payments->find()
                ->where(['user_id' => $userId]) // Changed from customer_id to user_id
                ->select(['total' => $this->Payments->find()->func()->sum('amount')])
                ->first();
            $totalPaid = $totalPaid ? $totalPaid->total : 0;

            $pendingOrdersCount = $this->MillingOrders->find()
                ->where(['user_id' => $userId, 'status' => 'Pending']) // Changed from customer_id to user_id
                ->count();
                
            $millingOrdersCountActive = $this->MillingOrders->find()
                ->where(['user_id' => $userId, 'status' => 'Milling']) // Changed from customer_id to user_id
                ->count();
                
            $completedOrdersCount = $this->MillingOrders->find()
                ->where(['user_id' => $userId, 'status' => 'Completed']) // Changed from customer_id to user_id
                ->count();

            // Recent orders for this customer
            $recentOrders = $this->MillingOrders->find()
                ->where(['user_id' => $userId]) // Changed from customer_id to user_id
                ->order(['MillingOrders.created' => 'DESC'])
                ->limit(5)
                ->all();

            // Recent payments for this customer
            $recentPayments = $this->Payments->find()
                ->where(['user_id' => $userId]) // Changed from customer_id to user_id
                ->order(['Payments.created' => 'DESC'])
                ->limit(5)
                ->all();

            $dashboardTitle = 'Customer Dashboard';
            
            $this->set(compact(
                'identity',
                'role',
                'dashboardTitle',
                'millingOrdersCount',
                'customersCount',
                'paymentsCount',
                'totalPaid',
                'pendingOrdersCount',
                'millingOrdersCountActive',
                'completedOrdersCount',
                'recentOrders',
                'recentPayments'
            ));
        } else {
            // Count only customer users
            $customersCount = $this->Users->find()->where(['role' => 'customer'])->count();
            $paymentsCount = $this->Payments->find()->count();
            $millingOrdersCount = $this->MillingOrders->find()->count();

            // Additional statistics for staff/owner
            $pendingOrdersCount = $this->MillingOrders->find()
                ->where(['status' => 'Pending'])
                ->count();
                
            $millingOrdersCountActive = $this->MillingOrders->find()
                ->where(['status' => 'Milling'])
                ->count();
                
            $completedOrdersCount = $this->MillingOrders->find()
                ->where(['status' => 'Completed'])
                ->count();

            // Recent orders
            $recentOrders = $this->MillingOrders->find()
                ->contain(['Users']) // Changed from Customers to Users
                ->order(['MillingOrders.created' => 'DESC'])
                ->limit(5)
                ->all();

            // Recent payments
            $recentPayments = $this->Payments->find()
                ->contain(['Users']) // Changed from Customers to Users
                ->order(['Payments.created' => 'DESC'])
                ->limit(5)
                ->all();

            // Total revenue
            $totalRevenue = $this->Payments->find()
                ->select(['total' => $this->Payments->find()->func()->sum('amount')])
                ->first();
            $totalRevenue = $totalRevenue ? $totalRevenue->total : 0;

            $dashboardTitle = ucfirst($role) . ' Dashboard';

            $this->set(compact(
                'identity',
                'role',
                'dashboardTitle',
                'millingOrdersCount',
                'customersCount',
                'paymentsCount',
                'pendingOrdersCount',
                'millingOrdersCountActive',
                'completedOrdersCount',
                'recentOrders',
                'recentPayments',
                'totalRevenue'
            ));
        }
    }
}