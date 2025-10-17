<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Milling Orders</h3>
    <?php if ($role !== 'customer'): ?>
        <?= $this->Html->link('Add Milling Order', ['action' => 'add'], ['class' => 'btn btn-primary btn-lg']) ?>
    <?php endif; ?>
</div>
<p>Only customers with active order/s are shown here.</p>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Weight</th>
            <th>Total Amount</th>
            <th>Total Paid</th>
            <th>Balance</th>
            <th>Status</th>
            <th>Delivery/Pickup Status</th>
            <th>Payment Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): 
            // Calculate total paid for this order
            $totalPaid = 0;
            if (!empty($order->payments)) {
                foreach ($order->payments as $payment) {
                    $totalPaid += $payment->amount;
                }
            }
            $balance = $order->total_amount - $totalPaid;
        ?>
        <tr>
            <td><?= $this->Number->format($order->id) ?></td>
            <td>
                <?php if ($order->has('user')): ?>
                    <?php if ($role !== 'customer'): ?>
                        <?= $this->Html->link(
                            $order->user->first_name . ' ' . $order->user->last_name, 
                            ['controller' => 'Users', 'action' => 'view', $order->user->id]
                        ) ?>
                    <?php else: ?>
                        <?= h($order->user->first_name . ' ' . $order->user->last_name) ?>
                    <?php endif; ?>
                <?php else: ?>
                    Unknown
                <?php endif; ?>
            </td>
            <td><?= $this->Number->format($order->weight) ?> kg</td>
            <td>₱<?= number_format($order->total_amount, 2) ?></td>
            <td>₱<?= number_format($totalPaid, 2) ?></td>
            <td>₱<?= number_format($balance, 2) ?></td>
            <td>
                <span class="badge 
                    <?= $order->status === 'Pending' ? 'bg-secondary' :
                       ($order->status === 'Milling' ? 'bg-warning' : 'bg-success') ?>">
                    <?= h($order->status) ?>
                </span>
            </td>
            <td>
                <span class="badge bg-info">
                    <?= h(ucwords(str_replace('_', ' ', $order->delivery_status))) ?>
                </span>
            </td>
            <td>
                <?php if ($order->payment_status === 'paid'): ?>
                    <span class="badge bg-success">Paid</span>
                <?php elseif ($order->payment_status === 'partial'): ?>
                    <span class="badge bg-warning">Partial</span>
                <?php else: ?>
                    <span class="badge bg-danger">Pending</span>
                <?php endif; ?>
            </td>
            <td class="actions">
                <?= $this->Html->link('View', ['action' => 'view', $order->id], ['class' => 'btn btn-info btn-sm']) ?>
                
                <?php if ($role !== 'customer'): ?>
                    <?= $this->Html->link('Edit', ['action' => 'edit', $order->id], ['class' => 'btn btn-primary btn-sm']) ?>
                    <?= $this->Html->link('Quick Update', ['action' => 'quickUpdate', $order->id], ['class' => 'btn btn-warning btn-sm']) ?>
                    <?= $this->Html->link('Add Payment', [
                        'controller' => 'Payments', 
                        'action' => 'add', 
                        '?' => ['milling_order_id' => $order->id]
                    ], ['class' => 'btn btn-success btn-sm']) ?>
                    <?= $this->Form->postLink('Delete', 
                        ['action' => 'delete', $order->id], 
                        [
                            'confirm' => 'Are you sure you want to delete this order?',
                            'class' => 'btn btn-danger btn-sm'
                        ]
                    ) ?>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>