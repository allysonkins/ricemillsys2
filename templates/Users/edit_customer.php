<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $customer
 * @var string $currentUserRole
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('View Customer'), ['action' => 'viewCustomer', $customer->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Customers'), ['action' => 'customers'], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(
                __('Delete Customer'),
                ['action' => 'deleteCustomer', $customer->id],
                ['confirm' => __('Are you sure you want to delete customer #{0}?', $customer->id), 'class' => 'side-nav-item text-danger']
            ) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($customer) ?>
            <fieldset>
                <legend><?= __('Edit Customer') ?></legend>
                
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
                            
                            if (in_array($currentUserRole, ['admin', 'owner'])) {
                                echo $this->Form->control('role', [
                                    'options' => ['customer' => 'Customer'],
                                    'required' => true
                                ]);
                            } else {
                                echo '<div class="input text">';
                                echo $this->Form->label('role', 'Role');
                                echo '<p><strong>Customer</strong></p>';
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

                <div class="row">
                    <div class="column">
                        <h3>Address Information</h3>
                        <?php
                            echo $this->Form->control('address', [
                                'type' => 'textarea',
                                'placeholder' => 'Full address',
                                'rows' => 4
                            ]);
                        ?>
                    </div>
                </div>

            </fieldset>
            <?= $this->Form->button(__('Save Changes'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'viewCustomer', $customer->id], ['class' => 'btn btn-secondary']) ?>
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
.text-danger {
    color: #dc3545;
}
</style>