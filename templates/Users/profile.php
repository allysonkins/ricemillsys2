<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users view content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>My Profile</h2>
        <div>
            <?= $this->Html->link('Edit Profile', ['action' => 'edit', $user->id], ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link('Back to Dashboard', ['controller' => 'Dashboard', 'action' => 'index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
    
    <div class="row">
        <div class="column column-50">
            <div class="card">
                <div class="card-header">
                    <h3>Account Information</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr><th><?= __('Username') ?></th><td><?= h($user->username) ?></td></tr>
                        <tr><th><?= __('Role') ?></th><td><?= h(ucfirst($user->role)) ?></td></tr>
                        <tr><th><?= __('Created') ?></th><td><?= h($user->created) ?></td></tr>
                        <tr><th><?= __('Modified') ?></th><td><?= h($user->modified) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="column column-50">
            <div class="card">
                <div class="card-header">
                    <h3>Personal Information</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr><th><?= __('First Name') ?></th><td><?= h($user->first_name) ?></td></tr>
                        <tr><th><?= __('Last Name') ?></th><td><?= h($user->last_name) ?></td></tr>
                        <tr><th><?= __('Email') ?></th><td><?= h($user->email) ?></td></tr>
                        <tr><th><?= __('Phone') ?></th><td><?= h($user->phone) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php if ($user->role === 'customer' && $user->customer): ?>
    <div class="row mt-4">
        <div class="column">
            <div class="card">
                <div class="card-header">
                    <h3>Customer Information</h3>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tr><th><?= __('Customer Name') ?></th><td><?= h($user->customer->name) ?></td></tr>
                        <tr><th><?= __('Address') ?></th><td><?= h($user->customer->address) ?></td></tr>
                        <tr><th><?= __('Contact Number') ?></th><td><?= h($user->customer->contact_number) ?></td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
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
}
</style>