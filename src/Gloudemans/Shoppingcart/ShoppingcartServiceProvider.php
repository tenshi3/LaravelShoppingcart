<?php

namespace Gloudemans\Shoppingcart;

use Illuminate\Support\ServiceProvider;

class ShoppingcartServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app['cart'] = $this->app->share(function ($app) {
            $session = $app['session'];
            $events = $app['events'];

            return new Cart($session, $events);
        });
    }
}
