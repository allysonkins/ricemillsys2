<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="row">
    <div class="column column-80">
        <div class="users form content">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <legend><?= __('Create Customer Account') ?></legend>
                <?php
                    echo $this->Form->control('username', [
                        'required' => true,
                        'placeholder' => 'Enter username'
                    ]);
                    echo $this->Form->control('password', [
                        'required' => true,
                        'placeholder' => 'Enter password (min 6 characters)',
                        'value' => ''
                    ]);

                    // ✅ Force the role for staff — no dropdown, no label
                    echo $this->Form->hidden('role', ['value' => 'customer']);

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
                ?>
            </fieldset>

            <!-- Buttons -->
            <?= $this->Form->button(__('Create Customer'), ['class' => 'btn btn-primary']) ?>
            <?= $this->Html->link(__('Cancel'), ['action' => 'customers'], ['class' => 'btn btn-secondary']) ?>
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
