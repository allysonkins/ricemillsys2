<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SystemSetting $systemSetting
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit System Setting'), ['action' => 'edit', $systemSetting->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete System Setting'), ['action' => 'delete', $systemSetting->id], ['confirm' => __('Are you sure you want to delete # {0}?', $systemSetting->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List System Settings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New System Setting'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="systemSettings view content">
            <h3><?= h($systemSetting->setting_key) ?></h3>
            <table>
                <tr>
                    <th><?= __('Setting Key') ?></th>
                    <td><?= h($systemSetting->setting_key) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($systemSetting->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($systemSetting->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($systemSetting->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Setting Value') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($systemSetting->setting_value)); ?>
                </blockquote>
            </div>
        </div>
    </div>
</div>