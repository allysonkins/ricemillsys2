<?php
declare(strict_types=1);

namespace App\View\Helper;

use Cake\View\Helper;
use Cake\View\View;

/**
 * Options helper
 */
class OptionsHelper extends Helper
{
    /**
     * Default configuration.
     *
     * @var array<string, mixed>
     */
    protected array $_defaultConfig = [];

    public function roles1()
    {
     $roles = [];
        if (in_array($currentUserRole, ['admin', 'owner'])) {
            $roles = [
                'admin' => 'Admin', 
                'staff' => 'Staff',
                
            ];
            return $array;
        }
}
}
