<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Payment $payment
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Payment'), ['action' => 'edit', $payment->id], ['class' => 'side-nav-item btn btn-primary btn-lg']) ?>
            <?= $this->Form->postLink(__('Delete Payment'), ['action' => 'delete', $payment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $payment->id), 'class' => 'side-nav-item btn btn-danger btn-lg']) ?>
            <?= $this->Html->link(__('List Payments'), ['action' => 'index'], ['class' => 'side-nav-item btn btn-secondary btn-lg']) ?>
            <!-- REMOVED: New Payment button since payments should only be added from Milling Orders -->
        </div>
    </aside>
    <div class="column column-80">
        <div class="payments view content">
            <h3>Payment #<?= h($payment->id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Customer') ?></th>
                    <td>
                        <?= $payment->has('user') ? 
                            $this->Html->link(
                                $payment->user->first_name . ' ' . $payment->user->last_name, 
                                ['controller' => 'Users', 'action' => 'view', $payment->user->id]
                            ) 
                            : 'Unknown Customer' ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Milling Order') ?></th>
                    <td>
                        <?php if ($payment->milling_order_id && $payment->has('milling_order')): ?>
                            <?= $this->Html->link(
                                'Order #' . $payment->milling_order->id, 
                                ['controller' => 'MillingOrders', 'action' => 'view', $payment->milling_order->id]
                            ) ?>
                        <?php else: ?>
                            <span class="text-muted">No milling order linked</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?= __('Amount') ?></th>
                    <td>₱<?= $this->Number->format($payment->amount, ['places' => 2]) ?></td>
                </tr>
                <tr>
                    <th><?= __('Payment Method') ?></th>
                    <td><?= h(ucfirst($payment->payment_method)) ?></td>
                </tr>
                <tr>
                    <th><?= __('Payment Date') ?></th>
                    <td><?= h($payment->payment_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($payment->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($payment->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Notes') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($payment->notes ?: 'No notes provided')); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>