<div class="users index content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><?= __('Customers') ?></h3>
        <?= $this->Html->link(__('New Customer'), ['action' => 'addcustomers'], ['class' => 'btn btn-primary']) ?>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('username') ?></th>
                    <th><?= $this->Paginator->sort('first_name') ?></th>
                    <th><?= $this->Paginator->sort('last_name') ?></th>
                    <th><?= $this->Paginator->sort('email') ?></th>
                    <th><?= $this->Paginator->sort('phone') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= h($customer->id) ?></td>
                    <td><?= h($customer->username) ?></td>
                    <td><?= h($customer->first_name) ?></td>
                    <td><?= h($customer->last_name) ?></td>
                    <td><?= h($customer->email) ?></td>
                    <td><?= h($customer->phone) ?></td>
                    <td><?= h($customer->created) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'viewCustomer', $customer->id], ['class' => 'btn btn-sm btn-info']) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'editCustomer', $customer->id], ['class' => 'btn btn-sm btn-warning']) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'deleteCustomer', $customer->id],
                            [
                                'confirm' => __('Are you sure you want to delete customer #{0}?', $customer->id),
                                'class' => 'btn btn-sm btn-danger'
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
        .btn { margin-right: 5px; text-decoration: none; display: inline-block; padding: 0.25rem 0.5rem; border: none; border-radius: 4px; font-size: 0.875rem; cursor: pointer; }
        .btn-primary { background: #007bff; color: white; }
        .btn-info { background: #17a2b8; color: white; }
        .btn-warning { background: #ffc107; color: #212529; }
        .btn-danger { background: #dc3545; color: white; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .d-flex { display: flex; }
        .justify-content-between { justify-content: space-between; }
        .align-items-center { align-items: center; }
        .mb-4 { margin-bottom: 1.5rem; }
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