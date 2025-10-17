<h1>Login</h1>
<?= $this->Form->create() ?>
<?= $this->Form->control('username') ?>
<?= $this->Form->control('password', ['type' => 'password']) ?>
<?= $this->Form->button(__('Login')) ?>
<?= $this->Form->end() ?>