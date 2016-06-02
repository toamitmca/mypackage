<?php
/**
 * EntityTrait
 *
 * @filesource
 */
namespace MyPackage\Ank\Entity;

/**
 * Methods (Magic, 'helpers', etc.) used across Entities.
 *
 * @package    MyPackage\Ank\Entity\EntityTrait
 * @version    1.0.1
 * @copyright  2016 Taylor & Francis Group
 * @author     Amit Kumar <toamitmca@gmail.com>
 */

trait EntityTrait
{

    /**
     * __get() is utilized for reading data from inaccessible properties.
     *
     * @param  string $key Property Key
     * @return mixed
     */
    public function __get($key)
    {
        if (is_string($key) && ! empty($key)) {
            $getter = 'get' . ucfirst($key);
            if (method_exists($this, $getter)) {
                return $this->$getter();
            } elseif (property_exists($this, $key)) {
                return $this->$key;
            }
        }

        return null;
    }

    /**
     * The __invoke() method is called when a script tries to call an object as a function.
     * Get/Set property from callable object, using a default return parameter
     *
     * @internal param string $input Property Key or Object (for injection)
     * @internal param mixed  $value Property Value
     * @return   mixed
     */
    public function __invoke()
    {
        // Get function arguments.
        $args = func_get_args();
        // Return false without a valid string.
        if ( ! isset($args[0]) || empty($args[0]) || ! is_string($args[0])) {
            return false;
        }
        // Return Property Value if input-value unavailable.
        if ( ! isset($args[1])) {
            return $this->__get($args[0]);
        }
        // Set Property Value and return $this.
        return $this->__set($args[0], $args[1]);
    }

    /**
     * __isset() is triggered by calling isset() or empty() on inaccessible properties.
     *
     * @param  string $key
     * @return bool   True if property is set.
     */
    public function __isset($key)
    {
        if (is_string($key) && ! empty($key) && property_exists($this, $key)) {
            return true;
        }

        return false;
    }

    /**
     * __set() is utilized for writing data to inaccessible properties.
     *
     * @param  string $key Property Key
     * @param  mixed  $val Property Value
     * @return bool   True if property was set.
     */
    public function __set($key, $val = null)
    {
        if (is_string($key) && ! empty($key)) {
            $setter = 'set' . ucfirst($key);
            if (method_exists($this, $setter)) {
                $this->$setter($val);
                return true;
            } elseif (property_exists($this, $key)) {
                $this->$key = $val;
                return true;
            }
        }

        return false;
    }

    /**
     * __unset() is invoked when unset() is used on inaccessible properties.
     *
     * @param  string $key Property Key
     * @return bool   True if property was unset.
     */
    public function __unset($key)
    {
        if (is_string($key) && ! empty($key) && property_exists($this, $key)) {
            unset($this->$key);
            return true;
        }

        return false;
    }

    /**
     * Prepare object for safe use of `json_encode($entity)`
     *
     * @return string JSON-encoded object
     */
    public function jsonSerialize()
    {
        $obj = (object) [get_called_class() => get_object_vars($this)];

        return json_encode($obj);
    }

}