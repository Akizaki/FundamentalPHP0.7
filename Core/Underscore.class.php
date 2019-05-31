<?php

/**
 * Description of Underscore
 * 
 */
class Underscore implements IteratorAggregate, Countable {

    protected $_values;

    /**
     * 
     * @param Underscore|Traversable|array $data
     */
    public function __construct($data)
    {
        $this->_values = $this->_toArray($data);
    }

    /**
     * 
     * @param callable $callback
     * @return Underscore
     */
    public function each(callable $callback)
    {
        foreach ($this->valueOf() as $key => $value)
        /* call_user_func_array($callback, [$value, $key]); */
            $callback($value, $key);
        return $this;
    }

    /**
     * 
     * @param callable $callback
     * @return Underscore
     */
    public function map(callable $callback)
    {
        $array = [];
        foreach ($this->valueOf() as $key => $value)
            $array[$key] = $callback($value, $key);
        return new self($array);
        /* return new self(array_map($callback, array_values($this->valueOf()), array_keys($this->valueOf()))); */
    }

    /**
     * 
     * @param callable $callback
     * @return Underscore
     */
    public function filter(callable $callback)
    {
        $array = [];
        foreach ($this->valueOf() as $key => $value)
            if ($callback($value, $key))
                $array[$key] = $value;
        return new self($array);
        /* PHP5.6
          return new self(array_filter($this->value(), $callback, ARRAY_FILTER_USE_BOTH));
         */
    }

    /**
     * 
     * @param callable $callback
     * @return boolean
     */
    public function some(callable $callback)
    {
        foreach ($this->valueOf() as $key => $value)
            if ($callback($value, $key))
                return true;
        return false;
    }

    /**
     * 
     * @param callable $callback
     * @return boolean
     */
    public function every(callable $callback)
    {
        foreach ($this->valueOf() as $key => $value)
            if (!$callback($value, $key))
                return false;
        return true;
    }

    /**
     * 
     * @param callable $callback
     * @return mixed
     */
    public function find(callable $callback)
    {
        foreach ($this->valueOf() as $value)
            if ($callback($value))
                return $value;
        return null;
    }

    /**
     * 
     * @param array $new_values
     * @return Underscore
     */
    public function concat(array $new_values)
    {
        return new self(array_merge($this->valueOf(), $new_values));
    }

    /**
     * 
     * @param callable $callback
     * @param null $init
     * @return mixed
     */
    public function reduce(callable $callback, $init = null)
    {
        return array_reduce($this->valueOf(), $callback, $init);
    }

    /**
     * 
     * @param callable $callback
     * @param type $init
     * @return mixed
     */
    public function reduceRight(callable $callback, $init = null)
    {
        return array_reduce(array_reverse($this->valueOf()), $callback, $init);
    }

    /**
     * 
     * @param string $property_name
     * @return Underscore
     */
    public function pluck($property_name)
    {
        return $this->map(function ($values) use($property_name) {
                    return isset($values[$property_name]) ? $values[$property_name] : null;
                });
    }

    /**
     * 
     * @param string $name
     * @param array $args
     * @return mixed
     */
    public static function __callStatic($name, $args)
    {
        $instance = new self($args[0]);
        return count($args) > 1 ?
                call_user_func_array([$instance, $name], array_slice($args, 1)) : $instance->$name();
    }

    /**
     * 
     * @param Underscore|Traversable|array $iterator
     * @return array
     */
    protected function _toArray($iterator)
    {
        if ($iterator instanceof self)
            return $iterator->valueOf();
        if ($iterator instanceof Traversable)
            return iterator_to_array($iterator);
        return (array) $iterator;
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return array_key_exists($name, $this->valueOf()) ? $this->_values[$name] : null;
    }

    /**
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function set($name, $value)
    {
        if (is_null($name) || !isset($name))
        /* array_push ($this->_values, $value); */
            $this->_values[] = $value;
        else
            $this->_values[$name] = $value;
    }

    /**
     * 
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * 
     * @param string $name
     * @return
     */
    public function remove($name)
    {
        if (array_key_exists($name, $this->valueOf()))
            return;
        unset($this->_values[$name]);
    }

    /**
     * 
     * @param string $name
     * @return boolean
     */
    public function has($name)
    {
        return array_key_exists($name, $this->valueOf());
    }

    /**
     * 
     * @param string $separator
     * @return string
     */
    public function join($separator = "")
    {
        return join($separator, $this->valueOf());
    }

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->join("");
    }

    /**
     * 
     * @return Underscore
     */
    public function keys()
    {
        return new self(array_keys($this->valueOf()));
    }

    /**
     * 
     * @return Underscore
     */
    public function values()
    {
        return new self(array_values($this->valueOf()));
    }

    /**
     * 
     * @param Underscore|Traversable|array $defaults
     * @return Underscore
     */
    protected function _defaults($defaults)
    {
        return new self(array_merge($this->_toArray($defaults), $this->valueOf()));
    }

    /**
     * 
     * @return array
     */
    public function valueOf()
    {
        return $this->_values;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->valueOf());
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->valueOf());
    }

}
