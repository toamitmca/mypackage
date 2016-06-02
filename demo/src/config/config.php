<?php
/*
|--------------------------------------------------------------------------
| Configuration parameters
|--------------------------------------------------------------------------
|
| This builds the array of configuration parameters
| that control the service.
|
*/

return call_user_func(function () {

    /**
     * @var array Configuration parameters array
     */
    $config = array();

    /**
     * Is this package available for the public?
     */
    $config['public'] = false;

    /**
     * Applicable Countries for T&F India distributors
     */
    $config['countries'] = [
        'IN' => 'India',
        'PK' => 'Pakistan',
        'NP' => 'Nepal',
        'BT' => 'Bhutan',
        'BD' => 'Bangladesh',
        'LK' => 'Sri Lanka',
    ];

    /**
     * Return configuration parameters
     *
     * @return array
     */
    return $config;

});
