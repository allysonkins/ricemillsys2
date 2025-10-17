<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Update - Order #<?= h($order->id) ?></h3>
                <?= $this->Html->link(__('Back to List'), ['action' => 'index'], ['class' => 'btn btn-secondary btn-sm float-end']) ?>
            </div>
            <div class="card-body">
                <div class="mb-4 p-3 bg-light rounded">
                    <strong>Customer:</strong> <?= h($order->user->first_name . ' ' . $order->user->last_name ?? 'Unknown') ?><br> <!-- CHANGED: customer to user -->
                    <strong>Weight:</strong> <?= h($order->weight) ?> kg<br>
                    <strong>Delivery Option:</strong> 
                    <span class="badge <?= $order->delivery_option === 'delivery' ? 'bg-primary' : 'bg-info' ?>">
                        <?= $order->delivery_option === 'delivery' ? 'Home Delivery' : 'Customer Pickup' ?>
                    </span><br>
                    <strong>Current Status:</strong> 
                    <span class="badge 
                        <?= $order->status === 'Pending' ? 'bg-secondary' :
                           ($order->status === 'Milling' ? 'bg-warning' : 'bg-success') ?>">
                        <?= h($order->status) ?>
                    </span><br>
                    <strong>Delivery/Pickup Date:</strong> <?= h($order->delivery_date) ?>
                </div>

                <?= $this->Form->create($order) ?>
                
                <div class="mb-3">
                    <?= $this->Form->control('status', [
                        'options' => $statusOptions,
                        'class' => 'form-select form-select-lg',
                        'label' => 'Update Milling Status',
                        'empty' => false
                    ]) ?>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('delivery_status', [
                        'options' => $deliveryStatusOptions,
                        'class' => 'form-select',
                        'label' => 'Update Delivery/Pickup Status',
                        'empty' => false
                    ]) ?>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('delivery_date', [
                        'label' => 'Delivery/Pickup Date',
                        'type' => 'date',
                        'class' => 'form-control'
                    ]) ?>
                </div>

                <div class="mb-3">
                    <?= $this->Form->control('payment_status', [
                        'options' => $paymentStatusOptions,
                        'class' => 'form-select',
                        'label' => 'Update Payment Status',
                        'empty' => false
                    ]) ?>
                </div>

                <div class="d-grid gap-2">
                    <?= $this->Form->button(__('Update Status'), [
                        'class' => 'btn btn-primary btn-lg'
                    ]) ?>
                    <?= $this->Html->link(__('Full Edit'), ['action' => 'edit', $order->id], [
                        'class' => 'btn btn-outline-secondary'
                    ]) ?>
                </div>

                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>