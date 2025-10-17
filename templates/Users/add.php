<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var array $roles
 * @var string $currentUserRole
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Users'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Add User') ?></legend>
                <?php
                    echo $this->Form->control('username', [
                        'required' => true,
                        'placeholder' => 'Enter username'
                    ]);
                    echo $this->Form->control('password', [
                        'required' => true,
                        'placeholder' => 'Enter password (min 6 characters)',
                        'value' => '' // Ensure password field is empty
                    ]);
                    
                    if (isset($roles) && !empty($roles)) {
                        echo $this->Form->control('role', [
                            'options' => $roles,
                            'required' => true,
                        ]);
                    } else {
                        // Display the role that will be assigned
                        echo '<div class="input text">';
                        echo $this->Form->label('role',['class'=>'form-control',
                    'options'=>$this->Options->roles(),'label'=>false]);
                        echo '<p><strong>Customer</strong> (Staff can only create customer accounts)</p>';
                        echo $this->Form->hidden('role', ['value' => 'customer']);
                        echo '</div>';
                    }
                    
                    echo $this->Form->control('first_name', [
                        'placeholder' => 'Optional'
                    ]);
                    echo $this->Form->control('last_name', [
                        'placeholder' => 'Optional'
                    ]);
                    echo $this->Form->control('email', [
                        'type' => 'email',
                        'placeholder' => 'Optional email address'
                    ]);
                    echo $this->Form->control('phone', [
                        'placeholder' => 'Optional phone number'
                    ]);
                    
                    // Only show customer_id field for staff/admin when creating customer accounts
                    if (isset($roles) && in_array('customer', array_keys($roles))) {
                        echo $this->Form->control('customer_id', [
                            'empty' => 'No customer linked',
                            'label' => 'Link to Customer (Optional)'
                        ]);
                    }
                ?>
            </fieldset>
            <?= $this->Form->button(__('Create User'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'index'], ['class' => 'btn btn-secondary']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<style>
    .btn {
        padding: 0.5rem 1rem;
        margin-right: 0.5rem;
        text-decoration: none;
        display: inline-block;
    }
    .btn-primary {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
    }
    .btn-secondary {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 4px;
    }
</style>