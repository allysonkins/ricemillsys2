<div class="payments form content">
    <h3>Add Payment</h3>

    <?= $this->Form->create($payment) ?>
    <fieldset>
        <legend>Payment Details</legend>

        <?php if (!empty($millingOrderId)): ?>
            <div class="alert alert-success">
                <strong>Linked to Milling Order #<?= $millingOrderId ?></strong>
            </div>
            
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Order Total:</strong><br>
                            <span class="h5 text-primary">₱<?= number_format($orderTotal, 2) ?></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Total Paid:</strong><br>
                            <span class="h5 text-success">₱<?= number_format($totalPaid, 2) ?></span>
                        </div>
                        <div class="col-md-4">
                            <strong>Balance Due:</strong><br>
                            <span class="h5 <?= $balance > 0 ? 'text-danger' : 'text-success' ?>">
                                ₱<?= number_format($balance, 2) ?>
                            </span>
                        </div>
                    </div>
                    <?php if ($balance <= 0): ?>
                        <div class="alert alert-warning mt-2 mb-0">
                            <i class="fas fa-exclamation-triangle"></i> This order has already been fully paid.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Hidden fields to ensure values are submitted -->
            <?= $this->Form->hidden('milling_order_id', ['value' => $millingOrderId]) ?>
            <?= $this->Form->hidden('user_id', ['value' => $payment->user_id]) ?> <!-- CHANGED: customer_id to user_id -->
            
            <div class="form-group">
                <label>Customer (Auto-filled from Order)</label>
                <input type="text" class="form-control" value="<?= h($payment->user->first_name . ' ' . $payment->user->last_name ?? 'Unknown Customer') ?>" readonly> <!-- CHANGED: customer to user -->
                <small class="form-text text-muted">Customer automatically selected from milling order</small>
            </div>
            
            <div class="form-group">
                <label>Linked Milling Order</label>
                <input type="text" class="form-control" value="Order #<?= $millingOrderId ?> - ₱<?= number_format($orderTotal, 2) ?>" readonly>
                <?= $this->Html->link('View Order Details', [
                    'controller' => 'MillingOrders', 
                    'action' => 'view', 
                    $millingOrderId
                ], ['class' => 'btn btn-sm btn-outline-primary mt-2', 'target' => '_blank']) ?>
            </div>
        <?php else: ?>
            <?= $this->Form->control('user_id', [ // CHANGED: customer_id to user_id
                'label' => 'Customer',
                'options' => $customers,
                'empty' => 'Select a customer',
                'required' => true,
                'class' => 'form-control'
            ]) ?>

            <?= $this->Form->control('milling_order_id', [
                'label' => 'Milling Order (Optional)',
                'options' => $millingOrders,
                'empty' => 'Select a milling order (optional)',
                'required' => false,
                'class' => 'form-control'
            ]) ?>
        <?php endif; ?>

        <?= $this->Form->control('amount', [
            'label' => 'Amount (₱)',
            'type' => 'number',
            'step' => '0.01',
            'min' => '0.01',
            'max' => $balance > 0 ? $balance : null,
            'required' => true,
            'class' => 'form-control',
            'placeholder' => 'Enter payment amount'
        ]) ?>

        <?php if (!empty($millingOrderId) && $balance > 0): ?>
            <div class="form-text text-info">
                <i class="fas fa-info-circle"></i> 
                Maximum suggested amount: ₱<?= number_format($balance, 2) ?>
            </div>
        <?php endif; ?>

        <?= $this->Form->control('payment_method', [
            'label' => 'Payment Method',
            'options' => $paymentMethods,
            'empty' => 'Select method',
            'required' => true,
            'class' => 'form-control'
        ]) ?>

        <?= $this->Form->control('payment_date', [
            'label' => 'Payment Date',
            'type' => 'datetime-local',
            'class' => 'form-control',
            'value' => date('Y-m-d\TH:i')
        ]) ?>

        <?= $this->Form->control('notes', [
            'label' => 'Notes (optional)',
            'type' => 'textarea',
            'rows' => 3,
            'class' => 'form-control',
            'placeholder' => 'Any additional notes about this payment...'
        ]) ?>
    </fieldset>

    <div class="mt-3">
        <?= $this->Form->button(__('Save Payment'), ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>

<?php if (empty($millingOrderId)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const baseUrl = '<?= $this->Url->build('/') ?>';
    const userSelect = document.getElementById('user-id'); // CHANGED: customer-id to user-id
    const millingOrderSelect = document.getElementById('milling-order-id');

    userSelect.addEventListener('change', function() { // CHANGED: customerSelect to userSelect
        const userId = this.value; // CHANGED: customerId to userId
        
        millingOrderSelect.innerHTML = '<option value="">Loading milling orders...</option>';
        millingOrderSelect.disabled = true;

        if (!userId) {
            millingOrderSelect.innerHTML = '<option value="">Select a milling order (optional)</option>';
            millingOrderSelect.disabled = false;
            return;
        }

        fetch(`${baseUrl}payments/getMillingOrdersByCustomer/${userId}`) // URL already updated in controller
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                millingOrderSelect.innerHTML = '';

                const emptyOption = document.createElement('option');
                emptyOption.value = '';
                emptyOption.textContent = 'Select a milling order (optional)';
                millingOrderSelect.appendChild(emptyOption);

                if (data && Object.keys(data).length > 0) {
                    for (const [id, label] of Object.entries(data)) {
                        const option = document.createElement('option');
                        option.value = id;
                        option.textContent = label;
                        millingOrderSelect.appendChild(option);
                    }
                } else {
                    const noOrdersOption = document.createElement('option');
                    noOrdersOption.value = '';
                    noOrdersOption.textContent = 'No milling orders found for this customer';
                    millingOrderSelect.appendChild(noOrdersOption);
                }
                millingOrderSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading milling orders:', error);
                millingOrderSelect.innerHTML = '<option value="">Error loading milling orders</option>';
                millingOrderSelect.disabled = false;
            });
    });

    // Trigger change if user is pre-selected
    if (userSelect.value) {
        userSelect.dispatchEvent(new Event('change'));
    }
});
</script>
<?php endif; ?>