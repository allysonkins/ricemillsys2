<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment $payment
 * @var string[]|\Cake\Collection\CollectionInterface $customers
 * @var string[]|\Cake\Collection\CollectionInterface $millingOrders
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $payment->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $payment->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Payments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="payments form content">
            <?= $this->Form->create($payment) ?>
            <fieldset>
                <legend><?= __('Edit Payment') ?></legend>
                <?php
                    echo $this->Form->control('user_id', [ // CHANGED: customer_id to user_id
                        'options' => $customers,
                        'label' => 'Customer',
                        'empty' => 'Select a customer',
                        'required' => true,
                        'class' => 'form-control'
                    ]);
                    echo $this->Form->control('milling_order_id', [
                        'label' => 'Milling Order (Optional)',
                        'options' => $millingOrders,
                        'empty' => 'Select a milling order (optional)',
                        'required' => false,
                        'class' => 'form-control'
                    ]);
                    echo $this->Form->control('amount', [
                        'label' => 'Amount (₱)',
                        'type' => 'number',
                        'step' => '0.01',
                        'min' => '0.01',
                        'required' => true,
                        'class' => 'form-control'
                    ]);
                    echo $this->Form->control('payment_method', [
                        'label' => 'Payment Method',
                        'options' => $paymentMethods,
                        'empty' => 'Select method',
                        'required' => true,
                        'class' => 'form-control'
                    ]);
                    echo $this->Form->control('payment_date', [
                        'label' => 'Payment Date',
                        'type' => 'datetime-local',
                        'class' => 'form-control'
                    ]);
                    echo $this->Form->control('notes', [
                        'label' => 'Notes (optional)',
                        'type' => 'textarea',
                        'rows' => 3,
                        'class' => 'form-control'
                    ]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>