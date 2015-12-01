<?php

namespace Gloudemans\Shoppingcart;

use Illuminate\Support\Collection;

class CartCollection extends Collection
{
    public $preloadModels = [];

    public $orderId = null;
}
