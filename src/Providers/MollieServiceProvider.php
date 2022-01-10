<?php
 
namespace Webkul\Mollie\Providers;
use Illuminate\Support\ServiceProvider;
use Webkul\Core;
 
/**
 *  Mollie service provider
 *
 * @author    shaiv roy 
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class MollieServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    
   
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'mollie');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'mollie');

        $this->app->register(ModuleServiceProvider::class);
        
    }
 
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {   
        $this->registerConfig();
    }

    protected function registerConfig()
    {   
        //this will merge payment method
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/MolliePaymentMethods.php', 'paymentmethods'
        );

        // add menu inside configuration  
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'   
        );

    }
}
