<?php
declare(strict_types=1);

namespace App\View;

use Cake\View\View;

class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like adding helpers.
     */
    public function initialize(): void
    {
        parent::initialize();

        // Default CakePHP helpers
        $this->loadHelper('Html');
        $this->loadHelper('Form');
        $this->loadHelper('Flash');

        // ✅ Authentication plugin Identity helper
        $this->loadHelper('Authentication.Identity');
    }
}
