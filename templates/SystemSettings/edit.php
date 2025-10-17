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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $systemSetting->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $systemSetting->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List System Settings'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column column-80">
        <div class="systemSettings form content">
            <?= $this->Form->create($systemSetting) ?>
            <fieldset>
                <legend><?= __('Edit System Setting') ?></legend>
                <?php
                    echo $this->Form->control('setting_key');
                    echo $this->Form->control('setting_value');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
