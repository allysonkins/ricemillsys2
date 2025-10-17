<div class="milling-orders view content">
    <h3>Milling Order #<?= h($order->id) ?></h3>
    
    <div class="card mb-4">
        <div class="card-header">
            <h4>Order Information</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th>Customer</th>
                    <td><?= h($order->user->first_name . ' ' . $order->user->last_name) ?></td>
                </tr>
                <tr>
                    <th>Weight</th>
                    <td><?= h($order->weight) ?> kg</td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td>₱<?= number_format($order->total_amount, 2) ?></td>
                </tr>
                <tr>
                    <th>Total Paid</th>
                    <td>₱<?= number_format($totalPaid, 2) ?></td>
                </tr>
                <tr>
                    <th>Balance</th>
                    <td>₱<?= number_format($order->total_amount - $totalPaid, 2) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <span class="badge bg-<?= 
                            $order->status === 'Completed' ? 'success' : 
                            ($order->status === 'Milling' ? 'warning' : 'secondary')
                        ?>">
                            <?= h($order->status) ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Delivery Option</th>
                    <td><?= h(ucfirst($order->delivery_option)) ?></td>
                </tr>
                <tr>
                    <th>Delivery/Pickup Status</th>
                    <td><?= h(ucwords(str_replace('_', ' ', $order->delivery_status))) ?></td>
                </tr>
                <tr>
                    <th>Delivery/Pickup Date</th>
                    <td><?= h($order->delivery_date) ?></td>
                </tr>
                <tr>
                    <th>Payment Status</th>
                    <td>
                        <span class="badge bg-<?= 
                            $order->payment_status === 'paid' ? 'success' : 
                            ($order->payment_status === 'partial' ? 'warning' : 'danger')
                        ?>">
                            <?= h(ucfirst($order->payment_status)) ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Created</th>
                    <td><?= h($order->created) ?></td>
                </tr>
                <tr>
                    <th>Modified</th>
                    <td><?= h($order->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php if (!empty($order->payments)): ?>
    <div class="card">
        <div class="card-header">
            <h4>Payment History</h4>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order->payments as $payment): ?>
                    <tr>
                        <td><?= h($payment->payment_date) ?></td>
                        <td>₱<?= number_format($payment->amount, 2) ?></td>
                        <td><?= h(ucfirst($payment->payment_method)) ?></td>
                        <td><?= h($payment->notes) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <div class="mt-4">
        <?php if ($role !== 'customer'): ?>
            <?= $this->Html->link('Edit Order', ['action' => 'edit', $order->id], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('Add Payment', [
                'controller' => 'Payments', 
                'action' => 'add', 
                '?' => ['milling_order_id' => $order->id]
            ], ['class' => 'btn btn-info']) ?>
            <?= $this->Form->postLink('Delete Order', 
                ['action' => 'delete', $order->id], 
                [
                    'confirm' => 'Are you sure you want to delete this milling order?',
                    'class' => 'btn btn-danger'
                ]
            ) ?>
        <?php endif; ?>
        <?= $this->Html->link('Back to List', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
</div>