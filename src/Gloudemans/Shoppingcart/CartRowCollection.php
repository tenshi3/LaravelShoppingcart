<?php namespace Gloudemans\Shoppingcart;

use Illuminate\Support\Collection;

class CartRowCollection extends Collection {

	/**
	 * The Eloquent model a cart is associated with
	 *
	 * @var string
	 */
	protected $associatedModel;

	/**
	 * An optional namespace for the associated model
	 *
	 * @var string
	 */
	protected $associatedModelNamespace;
	
	/**
	 * Instance of Eloquent Model for Caching
	 *
	 * @var null
	 */
	protected $associatedModelInstance = null;

	/**
	 * Constructor for the CartRowCollection
	 *
	 * @param array    $items
	 * @param string   $associatedModel
	 * @param string   $associatedModelNamespace
	 */
	public function __construct($items, $associatedModel, $associatedModelNamespace)
	{
		parent::__construct($items);

		$this->associatedModel = $associatedModel;
		$this->associatedModelNamespace = $associatedModelNamespace;
	}

	public function __get($arg)
	{
		if($this->has($arg))
		{
			return $this->get($arg);
		}

		if ($arg == strtolower($this->associatedModel))
		{
			if (empty($this->associatedModelInstance)) {
				$modelInstance = $this->associatedModelNamespace ? $this->associatedModelNamespace . '\\' . $this->associatedModel : $this->associatedModel;
				$this->associatedModelInstance = $modelInstance::find($this->id);
			}
			return $this->associatedModelInstance;
		}

		return null;
	}
	
	public function __isset($arg)
	{
		return $this->has($arg) || $arg == strtolower($this->associatedModel);
	}
	
	public function __sleep()
	{
		return ['items', 'associatedModel', 'associatedModelNamespace'];
	}

	public function search($search, $strict = false)
	{
		foreach($search as $key => $value)
		{
			if($key === 'options')
			{
				$found = $this->{$key}->search($value);
			}
			else
			{
				$found = ($this->{$key} === $value) ? true : false;
			}

			if( ! $found) return false;
		}

		return $found;
	}

}
