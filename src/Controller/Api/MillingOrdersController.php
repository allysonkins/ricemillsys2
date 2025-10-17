<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Cake\Datasource\EntityInterface;
use Cake\Datasource\Exception\RecordNotFoundException;

class MillingOrdersController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->loadModel('MillingOrders');
        $this->loadModel('Payments');
    }

    /**
     * List milling orders for the authenticated user.
     */
    public function index(): void
    {
        $this->request->allowMethod(['get']);

        $user = $this->requireAuthenticatedUser();
        if (!$user) {
            return;
        }

        $query = $this->MillingOrders->find()
            ->contain(['Users', 'Payments'])
            ->orderDesc('MillingOrders.created');

        if (strtolower((string)$user->role) === 'customer') {
            $query->where(['MillingOrders.user_id' => $user->id]);
        }

        $orders = [];
        foreach ($query as $order) {
            $orders[] = $this->serializeOrder($order);
        }

        $this->respondSuccess(['orders' => $orders]);
    }

    /**
     * View a single milling order.
     */
    public function view($id): void
    {
        $this->request->allowMethod(['get']);

        $user = $this->requireAuthenticatedUser();
        if (!$user) {
            return;
        }

        try {
            $order = $this->MillingOrders->get((int)$id, [
                'contain' => ['Users', 'Payments'],
            ]);
        } catch (RecordNotFoundException $exception) {
            $this->respondError('Milling order not found.', 404);
            return;
        }

        if (strtolower((string)$user->role) === 'customer' && (int)$order->user_id !== (int)$user->id) {
            $this->respondError('You are not authorized to view this order.', 403);
            return;
        }

        $this->respondSuccess(['order' => $this->serializeOrder($order)]);
    }

    /**
     * Create a new milling order.
     */
    public function add(): void
    {
        $this->request->allowMethod(['post']);

        $user = $this->requireAuthenticatedUser();
        if (!$user) {
            return;
        }

        $role = strtolower((string)$user->role);
        if (!in_array($role, ['admin', 'owner', 'staff'], true)) {
            $this->respondError('You are not authorized to create milling orders.', 403);
            return;
        }

        $data = $this->getRequestPayload();
        $data += [
            'status' => $data['status'] ?? 'Pending',
            'delivery_status' => $data['delivery_status'] ?? 'pending',
            'payment_status' => $data['payment_status'] ?? 'pending',
        ];

        if (isset($data['weight'])) {
            $data['weight'] = (float)$data['weight'];
        }

        if (isset($data['total_amount'])) {
            $data['total_amount'] = (float)$data['total_amount'];
        }

        $order = $this->MillingOrders->newEmptyEntity();
        $order = $this->MillingOrders->patchEntity($order, $data);

        if ($order->hasErrors()) {
            $this->respondError('Unable to create milling order.', 422, [
                'validationErrors' => $order->getErrors(),
            ]);
            return;
        }

        if (!$this->MillingOrders->save($order)) {
            $this->respondError('Unable to save milling order. Please try again.', 500);
            return;
        }

        $createdOrder = $this->MillingOrders->get($order->id, [
            'contain' => ['Users', 'Payments'],
        ]);

        $this->respondSuccess([
            'order' => $this->serializeOrder($createdOrder),
        ], 201);
    }

    /**
     * Transform a milling order entity into an API friendly array.
     */
    protected function serializeOrder(EntityInterface $order): array
    {
        $payments = [];
        $totalPaid = 0.0;

        if (!empty($order->payments)) {
            foreach ($order->payments as $payment) {
                $amount = isset($payment->amount) ? (float)$payment->amount : 0.0;

                $paymentDate = null;
                if (isset($payment->payment_date)) {
                    $rawPaymentDate = $payment->payment_date;
                    if ($rawPaymentDate instanceof \DateTimeInterface) {
                        $paymentDate = $rawPaymentDate->format('Y-m-d H:i:s');
                    } elseif (is_string($rawPaymentDate)) {
                        $paymentDate = $rawPaymentDate;
                    }
                }

                $payments[] = [
                    'id' => isset($payment->id) ? (int)$payment->id : null,
                    'amount' => $amount,
                    'payment_method' => $payment->payment_method ?? null,
                    'payment_date' => $paymentDate,
                    'notes' => $payment->notes ?? null,
                ];
                $totalPaid += $amount;
            }
        }

        $customerName = null;
        if (isset($order->user)) {
            $firstName = $order->user->first_name ?? '';
            $lastName = $order->user->last_name ?? '';
            $customerName = trim($firstName . ' ' . $lastName) ?: $order->user->username;
        }

        $totalAmount = isset($order->total_amount) ? (float)$order->total_amount : null;

        $deliveryDate = null;
        if (isset($order->delivery_date)) {
            $rawDeliveryDate = $order->delivery_date;
            if ($rawDeliveryDate instanceof \DateTimeInterface) {
                $deliveryDate = $rawDeliveryDate->format('Y-m-d');
            } elseif (is_string($rawDeliveryDate)) {
                $deliveryDate = $rawDeliveryDate;
            }
        }

        $created = null;
        if (isset($order->created)) {
            $rawCreated = $order->created;
            if ($rawCreated instanceof \DateTimeInterface) {
                $created = $rawCreated->format('Y-m-d H:i:s');
            } elseif (is_string($rawCreated)) {
                $created = $rawCreated;
            }
        }

        $modified = null;
        if (isset($order->modified)) {
            $rawModified = $order->modified;
            if ($rawModified instanceof \DateTimeInterface) {
                $modified = $rawModified->format('Y-m-d H:i:s');
            } elseif (is_string($rawModified)) {
                $modified = $rawModified;
            }
        }

        return [
            'id' => isset($order->id) ? (int)$order->id : null,
            'user_id' => isset($order->user_id) ? (int)$order->user_id : null,
            'customer_name' => $customerName,
            'weight' => isset($order->weight) ? (float)$order->weight : null,
            'total_amount' => $totalAmount,
            'status' => $order->status ?? null,
            'delivery_option' => $order->delivery_option ?? null,
            'delivery_status' => $order->delivery_status ?? null,
            'delivery_date' => $deliveryDate,
            'payment_status' => $order->payment_status ?? null,
            'notes' => $order->notes ?? null,
            'total_paid' => round($totalPaid, 2),
            'outstanding_balance' => $totalAmount !== null ? round($totalAmount - $totalPaid, 2) : null,
            'created' => $created,
            'modified' => $modified,
            'payments' => $payments,
        ];
    }
}