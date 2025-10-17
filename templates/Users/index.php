<div class="users index content">
    <h3><?= __('Users') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= __('ID') ?></th>
                    <th><?= __('Username') ?></th>
                    <th><?= __('Role') ?></th>
                    <th><?= __('Email') ?></th>
                    <th><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= h($user->id) ?></td>
                    <td><?= h($user->username) ?></td>
                    <td><?= h($user->role) ?></td>
                    <td><?= h($user->email) ?></td>
                    <td>
                        <?= $this->Html->link('View', ['action' => 'view', $user->id]) ?>
                        <?= $this->Html->link('Edit', ['action' => 'edit', $user->id]) ?>
                        <?= $this->Form->postLink('Delete', ['action' => 'delete', $user->id], ['confirm' => 'Are you sure?']) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
