<?php

namespace Gloudemans\Shoppingcart;

use Illuminate\Support\Collection;

class CartRowCollection extends Collection
{
    /**
     * The Eloquent model a cart is associated with.
     *
     * @var string
     */
    protected $associatedModel;

    /**
     * An optional namespace for the associated model.
     *
     * @var string
     */
    protected $associatedModelNamespace;

    /**
     * Constructor for the CartRowCollection.
     *
     * @param array  $items
     * @param string $associatedModel
     * @param string $associatedModelNamespace
     */
    public function __construct($items, $associatedModel, $associatedModelNamespace)
    {
        parent::__construct($items);

        $this->associatedModel = $associatedModel;
        $this->associatedModelNamespace = $associatedModelNamespace;
    }

    public function __get($arg)
    {
        if ($this->has($arg)) {
            return $this->get($arg);
        }

        if ($arg == strtolower($this->associatedModel)) {
            $cache = \App::make('cart')->cache();

            if (isset($cache[$this->associatedModelNamespace.'\\'.$this->associatedModel])) {
                return $cache[$this->associatedModelNamespace.'\\'.$this->associatedModel]->find($this->id);
            } else {
                return;
            }
        }

        return;
    }

    public function __isset($arg)
    {
        return $this->has($arg) || $arg == strtolower($this->associatedModel);
    }

    public function search($search, $strict = false)
    {
        foreach ($search as $key => $value) {
            if ($key === 'options') {
                $found = $this->{$key}->search($value);
            } else {
                $found = ($this->{$key} === $value) ? true : false;
            }

            if (!$found) {
                return false;
            }
        }

        return $found;
    }
}
