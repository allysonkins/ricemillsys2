<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $customer
 */
?>
<div class="users view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Customer Profile</h2>
        <div>
            <?= $this->Html->link('Edit Customer', ['action' => 'editCustomer', $customer->id], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('Back to Customers', ['action' => 'customers'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="column column-50">
            <div class="card">
                <div class="card-header">
                    <h3>Customer Information</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr><th><?= __('Username') ?></th><td><?= h($customer->username) ?></td></tr>
                        <tr><th><?= __('First Name') ?></th><td><?= h($customer->first_name) ?></td></tr>
                        <tr><th><?= __('Last Name') ?></th><td><?= h($customer->last_name) ?></td></tr>
                        <tr><th><?= __('Email') ?></th><td><?= h($customer->email) ?></td></tr>
                        <tr><th><?= __('Phone') ?></th><td><?= h($customer->phone) ?></td></tr>
                        <tr><th><?= __('Customer ID') ?></th><td><?= h($customer->id) ?></td></tr>
                        <tr><th><?= __('Created') ?></th><td><?= h($customer->created) ?></td></tr>
                        <tr><th><?= __('Modified') ?></th><td><?= h($customer->modified) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="column column-50">
            <div class="card">
                <div class="card-header">
                    <h3>Address Information</h3>
                </div>
                <div class="card-body">
                    <div class="address-info">
                        <strong><?= __('Address') ?></strong>
                        <div class="address-content">
                            <?= $this->Text->autoParagraph(h($customer->address)); ?>
                            <?php if (empty($customer->address)): ?>
                                <p class="text-muted">No address provided</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: 1px solid #e3e6f0;
    border-radius: 0.35rem;
    margin-bottom: 1rem;
}
.card-header {
    background: #f8f9fc;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e3e6f0;
}
.card-header h3 {
    margin: 0;
    color: #4e73df;
    font-size: 1.25rem;
}
.card-body {
    padding: 1.25rem;
}
.table {
    width: 100%;
    margin-bottom: 0;
}
.table th {
    width: 30%;
    font-weight: 600;
    color: #6e707e;
}
.table td {
    color: #858796;
}
.btn {
    margin-left: 0.5rem;
    text-decoration: none;
    display: inline-block;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    font-size: 0.875rem;
    cursor: pointer;
}
.btn-primary {
    background: #007bff;
    color: white;
}
.btn-secondary {
    background: #6c757d;
    color: white;
}
.text-muted {
    color: #6c757d !important;
}
.address-info {
    margin-bottom: 1rem;
}
.address-content {
    margin-top: 0.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 4px;
    border-left: 4px solid #007bff;
}
.d-flex {
    display: flex;
}
.justify-content-between {
    justify-content: space-between;
}
.align-items-center {
    align-items: center;
}
.mb-4 {
    margin-bottom: 1.5rem;
}
</style>