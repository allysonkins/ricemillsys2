<div class="milling-orders form content">
    <h3>Update Milling Order #<?= h($order->id) ?></h3>
    
    <div class="mb-3 p-3 bg-light rounded">
        <strong>Customer:</strong> 
        <?= h($order->has('user') ? $order->user->first_name . ' ' . $order->user->last_name : 'Unknown Customer') ?><br>
        <strong>Weight:</strong> <?= h($order->weight) ?> kg<br>
        <strong>Total Amount:</strong> ₱<?= number_format($order->total_amount, 2) ?><br>
        <strong>Total Paid:</strong> ₱<?= number_format($totalPaid, 2) ?><br>
        <strong>Balance:</strong> ₱<?= number_format($order->total_amount - $totalPaid, 2) ?>
    </div>

    <?= $this->Form->create($order) ?>
    <fieldset>
        <legend>Order Details</legend>
        
        <!-- Display customer as read-only -->
        <div class="form-group">
            <label>Customer</label>
            <input type="text" class="form-control" 
                   value="<?= h($order->has('user') ? $order->user->first_name . ' ' . $order->user->last_name : 'Unknown Customer') ?>" 
                   readonly>
            <small class="form-text text-muted">Customer cannot be changed for existing orders</small>
            <?= $this->Form->hidden('user_id', ['value' => $order->user_id]) ?>
        </div>
        
        <?= $this->Form->control('weight', [
            'label' => 'Weight (kg)', 
            'type' => 'number', 
            'step' => '0.01',
            'class' => 'form-control'
        ]) ?>
        
        <?= $this->Form->control('total_amount', [
            'label' => 'Total Amount (₱)', 
            'type' => 'number', 
            'step' => '0.01',
            'class' => 'form-control',
            'required' => true
        ]) ?>
        
        <?= $this->Form->control('delivery_option', [
            'label' => 'Delivery Option',
            'options' => $deliveryOptions,
            'type' => 'radio',
            'class' => 'form-check-input'
        ]) ?>
        
        <?= $this->Form->control('status', [
            'label' => 'Milling Status',
            'options' => $statusOptions,
            'class' => 'form-control'
        ]) ?>
        
        <legend>Delivery Details</legend>
        
        <?= $this->Form->control('delivery_status', [
            'label' => 'Delivery/Pickup Status',
            'options' => $deliveryStatusOptions,
            'class' => 'form-control'
        ]) ?>
        
        <?= $this->Form->control('delivery_date', [
            'label' => 'Delivery/Pickup Date',
            'type' => 'date',
            'class' => 'form-control'
        ]) ?>
    </fieldset>
    
    <div class="mt-3">
        <?= $this->Form->button('Update Order', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        <?= $this->Html->link('Add Payment', [
            'controller' => 'Payments', 
            'action' => 'add', 
            '?' => ['milling_order_id' => $order->id]
        ], ['class' => 'btn btn-info']) ?>
        <?= $this->Html->link('View Payments', [
            'controller' => 'Payments', 
            'action' => 'index'
        ], ['class' => 'btn btn-outline-primary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>