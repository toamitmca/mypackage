<?php
/**
 * Service
 *
 * @filesource
 */
namespace MyPackage\Ank\Service;

use MyPackage\Ank\Support\Config;

/**
 * Base class for all Service classes.
 *
 * @package    MyPackage\Ank\Service\Service
 * @version    1.0.1
 * @copyright  2016 Taylor & Francis Group
 * @author     Amit Kumar <toamitmca@gmail.com>
 */
abstract class Service
{

    /**
     * @var array Config parameters
     */
    protected $config;

    /**
     * Constructor
     *
     * @param array $config Configuration parameters
     */
    public function __construct(array $config = array())
    {
        // Set configuration parameters
        $this->config = Config::get($config);
    }

    /**
     * Get Applicable Countries
     *
     * @return array
     */
    public function getApplicableCountries()
    {
        return $this->config['countries'];
    }

    /**
     * Get Applicable Country Codes
     *
     * @return array
     */
    public function getApplicableCountryCodes()
    {
        return array_keys($this->config['countries']);
    }

    /**
     * Applicable Country Names for presentation
     *
     * @return string Country names for use in text/HTML.
     */
    public function printApplicableCountryNames()
    {
        $countries = array_values($this->getApplicableCountries());
        array_walk($countries, function(&$value) {
            $value = preg_replace('/ /', '&nbsp;', $value);
        });
        $str = array_pop($countries);
        $countryNames = implode(', ', $countries);
        $str = $countryNames . ' and&nbsp;' . $str;

        return $str;
    }

    /**
     * Is Applicable Country Code?
     *
     * @param  string $countryCode ISO Country Code
     * @return bool
     */
    public function isApplicableCountryCode($countryCode)
    {
        $countryCodes = $this->getApplicableCountryCodes();

        return (is_string($countryCode)) ? in_array($countryCode, $countryCodes) : false;
    }

}