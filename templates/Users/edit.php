<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $roles
 * @var string $currentUserRole
 * @var array $customers
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?php if ((int)$this->request->getAttribute('identity')->id === (int)$user->id): ?>
                <?= $this->Html->link(__('View Profile'), ['action' => 'profile'], ['class' => 'side-nav-item']) ?>
            <?php else: ?>
                <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
                <?= $this->Html->link(__('View User'), ['action' => 'view', $user->id], ['class' => 'side-nav-item']) ?>
            <?php endif; ?>
            
            <?php if (in_array($currentUserRole, ['admin', 'owner']) && (int)$this->request->getAttribute('identity')->id !== (int)$user->id): ?>
                <?= $this->Form->postLink(
                    __('Delete User'),
                    ['action' => 'delete', $user->id],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $user->id), 'class' => 'side-nav-item']
                ) ?>
            <?php endif; ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Edit User') ?></legend>
                
                <?php if ($currentUserRole === 'staff' && $user->role !== 'customer' && (int)$this->request->getAttribute('identity')->id !== (int)$user->id): ?>
                    <div class="message error">
                        <strong>Note:</strong> As staff, you can only edit customer accounts and your own profile.
                    </div>
                <?php endif; ?>
                
                <div class="row">
                    <div class="column">
                        <h3>Account Information</h3>
                        <?php
                            echo $this->Form->control('username', [
                                'required' => true,
                                'placeholder' => 'Enter username'
                            ]);
                            
                            echo $this->Form->control('password', [
                                'placeholder' => 'Leave blank to keep current password',
                                'value' => '',
                                'help' => 'Leave blank if you don\'t want to change the password'
                            ]);
                            
                            if (isset($roles) && !empty($roles) && in_array($currentUserRole, ['admin', 'owner'])) {
                                echo $this->Form->control('role', [
                                    'options' => $roles,
                                    'required' => true
                                ]);
                            } else {
                                echo '<div class="input text">';
                                echo $this->Form->label('role', 'Role');
                                echo '<p><strong>' . ucfirst($user->role) . '</strong></p>';
                                if ($currentUserRole === 'staff') {
                                    echo '<small>Staff cannot change user roles</small>';
                                }
                                echo '</div>';
                            }
                        ?>
                    </div>
                </div>

                <div class="row">
                    <div class="column">
                        <h3>Personal Information</h3>
                        <?php
                            echo $this->Form->control('first_name', [
                                'placeholder' => 'First Name'
                            ]);
                            
                            echo $this->Form->control('last_name', [
                                'placeholder' => 'Last Name'
                            ]);
                            
                            echo $this->Form->control('email', [
                                'type' => 'email',
                                'placeholder' => 'Email address'
                            ]);
                            
                            echo $this->Form->control('phone', [
                                'placeholder' => 'Phone number'
                            ]);
                        ?>
                    </div>
                </div>

                <?php if (in_array($currentUserRole, ['admin', 'owner'])): ?>
                <div class="row">
                    <div class="column">
                        <h3>Advanced Settings</h3>
                        <?php
                            echo $this->Form->control('customer_id', [
                                'options' => $customers,
                                'empty' => 'No customer linked',
                                'label' => 'Link to Customer'
                            ]);
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($user->role === 'customer' && $user->customer): ?>
                <div class="row">
                    <div class="column">
                        <h3>Customer Information</h3>
                        <?php
                            echo $this->Form->control('address', [
                                'value' => $user->customer->address ?? '',
                                'placeholder' => 'Customer address',
                                'label' => 'Address'
                            ]);
                        ?>
                        <div class="input text">
                            <label>Customer Name</label>
                            <p><strong><?= h($user->customer->name ?? 'Not set') ?></strong></p>
                            <small>This is automatically generated from first and last name</small>
                        </div>
                        <div class="input text">
                            <label>Contact Number</label>
                            <p><strong><?= h($user->customer->contact_number ?? 'Not set') ?></strong></p>
                            <small>This is synced with your phone number</small>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </fieldset>
            <?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'profile'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .btn {
        padding: 0.75rem 1.5rem;
        margin-right: 0.5rem;
        text-decoration: none;
        display: inline-block;
        border: none;
        border-radius: 4px;
        font-size: 1rem;
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
    .row {
        margin-bottom: 2rem;
    }
    .column h3 {
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
        color: #007bff;
    }
    .input text p {
        margin: 0.5rem 0;
        padding: 0.5rem;
        background: #f8f9fa;
        border-radius: 4px;
    }
    small {
        color: #6c757d;
        font-style: italic;
    }
    .message.error {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 4px;
        margin-bottom: 1rem;
        border: 1px solid #f5c6cb;
    }
</style>