<div class="milling-orders form content">
    <h3>Create New Milling Order</h3>

    <?= $this->Form->create($order) ?>
    <fieldset>
        <legend>Order Details</legend>
        
        <?= $this->Form->control('user_id', [ // CHANGED: customer_id to user_id
            'options' => $customers, 
            'label' => 'Customer',
            'class' => 'form-control',
            'required' => true,
            'empty' => 'Select Customer'
        ]) ?>
        
        <?= $this->Form->control('weight', [
            'label' => 'Weight (kg)', 
            'type' => 'number', 
            'step' => '0.01',
            'min' => '0.01',
            'class' => 'form-control',
            'required' => true,
            'placeholder' => 'Enter weight in kilograms'
        ]) ?>
        
        <?= $this->Form->control('total_amount', [
            'label' => 'Total Amount (₱)', 
            'type' => 'number', 
            'step' => '0.01',
            'min' => '0',
            'class' => 'form-control',
            'required' => true,
            'placeholder' => '0.00'
        ]) ?>
        
        <div class="form-group">
            <label>Delivery Option</label>
            <div>
                <?= $this->Form->radio('delivery_option', $deliveryOptions, [
                    'class' => 'form-check-input',
                    'default' => 'pickup'
                ]) ?>
            </div>
        </div>

        <?= $this->Form->control('delivery_date', [
            'label' => 'Delivery/Pickup Date',
            'type' => 'date',
            'class' => 'form-control'
        ]) ?>
    </fieldset>
    
    <div class="mt-3">
        <?= $this->Form->button('Create Order', ['class' => 'btn btn-primary']) ?>
        <?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
    </div>
    <?= $this->Form->end() ?>
</div>