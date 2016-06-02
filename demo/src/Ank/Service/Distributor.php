<?php
/**
 * Distributor
 *
 * @filesource
 */
namespace MyPackage\Ank\Service;

use MyPackage\Ank\Entity\Distributor as DistributorEntity;

/**
 * Service for providing Distributor data.
 *
 * @package    MyPackage\Ank\Service\Distributor
 * @version    1.0.1
 * @copyright  2016 Taylor & Francis Group
 * @author     Amit Kumar <toamitmca@gmail.com>
 */

class Distributor extends Service
{

    /**
     * Load methods used across Services.
     */
    use ServiceTrait;

    /**
     * @var array Distributors
     */
    private $distributors;

    /**
     * Constructor
     */
    public function __construct()
    {
        $distributors = file_get_contents(__DIR__ . '/../../data/distributors.json');
        $this->distributors = json_decode($distributors);
        // Load service and configuration
        parent::__construct();
    }

    /**
     * Get all distributors.
     *
     * @return array
     */
    public function all()
    {
        $ret = array();
        if (is_array($this->distributors) && ! empty($this->distributors)) {
            foreach ($this->distributors as $distributor) {
                if (is_object($distributor)) {
                    $ret[] = new DistributorEntity((array) $distributor);
                }
            }
        }

        return $ret;
    }

    /**
     * Find a distributor.
     *
     * @param  int|string|array $param Identifier, name, or key-value pair.
     * @return null|DistributorEntity
     */
    public function find($param)
    {
        if (is_int($param)) {
            return $this->findById($param);
        } elseif (is_string($param)) {
            if ($distributor = $this->findById($param)) {
                return $distributor;
            } elseif ($distributor = $this->findByName($param)) {
                return $distributor;
            }
        } elseif (is_array($param)) {
            foreach ($param as $k => $v) {
                return $this->findByKey($k, $v);
            }
        }

        return null;
    }

    /**
     * Find a distributor by property key and matching value.
     *
     * @param  string $key Property Key
     * @param  mixed  $val Property Value
     * @return null|DistributorEntity
     */
    public function findByKey($key, $val)
    {
        if (
            is_string($key) && ! empty($val)
            && is_array($this->distributors) && ! empty($this->distributors)
        ) {
            foreach ($this->distributors as $distributor) {
                if (
                    is_object($distributor) && isset($distributor->$key)
                    && $distributor->$key == $val
                ) {
                    return new DistributorEntity((array) $distributor);
                }
            }
        }

        return null;
    }

    /**
     * Find a distributor by ID.
     *
     * @param  int $id Identifier
     * @return null|DistributorEntity
     */
    public function findById($id)
    {
        return $this->findByKey('id', $id);
    }

    /**
     * Find a distributor by name.
     *
     * @param  string $name Name
     * @return null|DistributorEntity
     */
    public function findByName($name)
    {
        return $this->findByKey('name', $name);
    }

    /**
     * Find all distributors for an array of Identifiers.
     *
     * @param  array $id Array of Identifiers
     * @return null|array Array of DistributorEntity objects
     */
    public function findAllById(array $id)
    {
        if ( ! empty($id)) {
            $distributors = [];
            foreach ($id as $val) {
                if ($distributor = $this->findById($val)) {
                    $distributors[] = $distributor;
                }
            }
            if ( ! empty($distributors)) {
                return $distributors;
            }
        }

        return null;
    }

    /**
     * Get Listing of All Distributors
     *
     * @return string|null HTML
     */
    public function getListingOfAllDistributors()
    {
        $str = null;
        if ($distributors = $this->all()) {
            $str = $this->getListingOfDistributors($distributors);
        }

        return $str;
    }

    /**
     * Get Listing of Distributors By ID
     *
     * @param  array $id Array of Identifiers
     * @return string|null HTML
     */
    public function getListingOfDistributorsById(array $id)
    {
        $str = null;
        if ($distributors = $this->findAllById($id)) {
            $str = $this->getListingOfDistributors($distributors);
        }

        return $str;
    }

    /**
     * Get Listing of Distributors
     *
     * @param  array $distributors Array of Distributors
     * @return string HTML
     */
    public function getListingOfDistributors(array $distributors)
    {
        $str = '';
        foreach ($distributors as $distributor) {
            if (is_object($distributor) and ($distributor instanceof DistributorEntity)) {
                $str .= $this->getDistributorListing($distributor);
            }
        }

        return $str;
    }

    /**
     * Get Distributor Listing
     *
     * @param  DistributorEntity $distributor
     * @return string HTML
     */
    public function getDistributorListing(DistributorEntity $distributor)
    {
        return $distributor->printDistributorAddress();
    }

}