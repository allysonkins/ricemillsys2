<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\SystemSetting> $systemSettings
 */
?>
<div class="systemSettings index content">
    <?= $this->Html->link(__('New System Setting'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('System Settings') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('setting_key') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($systemSettings as $systemSetting): ?>
                <tr>
                    <td><?= $this->Number->format($systemSetting->id) ?></td>
                    <td><?= h($systemSetting->setting_key) ?></td>
                    <td><?= h($systemSetting->created) ?></td>
                    <td><?= h($systemSetting->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $systemSetting->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $systemSetting->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $systemSetting->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $systemSetting->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <style>
        .paginator { text-align: center; margin-top: 1rem; }
        .paginator .pagination { display: inline-block; padding: 0; margin: 0; list-style: none; }
        .paginator .pagination li { display: inline-block; margin: 0 .25rem; }
        .paginator .pagination a, .paginator .pagination span { padding: .4rem .75rem; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; }
        .paginator .pagination a:hover { background: #f5f5f5; }
        .paginator .pagination .current { background: #007bff; color: #fff; border-color: #007bff; }
        .paginator .counter { margin-top: .5rem; color: #555; }
    </style>

    <div class="paginator" role="navigation" aria-label="Pagination">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('&laquo; ' . __('previous'), ['escape' => false]) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' &raquo;', ['escape' => false]) ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p class="counter"><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>