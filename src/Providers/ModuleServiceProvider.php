<?php

namespace Webkul\Mollie\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Mollie\Models\Mollie::class,
    
    ];
}