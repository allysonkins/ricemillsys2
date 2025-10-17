<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Payments</h3>
    <!-- REMOVED: Add Payment button since payments should only be added from Milling Orders -->
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Milling Order</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Payment Date</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $payment): ?>
        <tr>
            <td><?= $this->Number->format($payment->id) ?></td>
            <td>
                <?= $payment->has('user') ? 
                    $this->Html->link(
                        $payment->user->first_name . ' ' . $payment->user->last_name, 
                        ['controller' => 'Users', 'action' => 'view', $payment->user->id]
                    ) 
                    : 'Unknown Customer' ?>
            </td>
            <td>
                <?php if ($payment->milling_order_id && $payment->has('milling_order') && $payment->milling_order !== null): ?>
                    <?= $this->Html->link('Order #' . $payment->milling_order->id, 
                        ['controller' => 'MillingOrders', 'action' => 'view', $payment->milling_order->id]) ?>
                <?php else: ?>
                    <span class="text-muted">No order linked</span>
                <?php endif; ?>
            </td>
            <td>₱<?= $this->Number->format($payment->amount, ['places' => 2]) ?></td>
            <td><?= h(ucfirst($payment->payment_method)) ?></td>
            <td><?= h($payment->payment_date) ?></td>
            <td><?= h($payment->created) ?></td>
            <td class="actions">
                <?= $this->Html->link('View', ['action' => 'view', $payment->id], ['class' => 'btn btn-info btn-lg']) ?>
                <?= $this->Html->link('Edit', ['action' => 'edit', $payment->id], ['class' => 'btn btn-primary btn-lg']) ?>
                <?= $this->Form->postLink('Delete', 
                    ['action' => 'delete', $payment->id], 
                    [
                        'confirm' => 'Are you sure you want to delete this payment?',
                        'class' => 'btn btn-danger btn-lg'
                    ]
                ) ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>