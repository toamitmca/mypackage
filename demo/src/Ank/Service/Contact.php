<?php
/**
 * Contact
 *
 * @filesource
 */
namespace MyPackage\Ank\Service;

/**
 * Service for providing Contact information.
 *
 * @package    MyPackage\Ank\Service\Contact
 * @version    1.0.1
 * @copyright  2016 Taylor & Francis Group
 * @author     Amit Kumar <toamitmca@gmail.com>
 */

class Contact extends Service
{

    /**
     * Load methods used across Services.
     */
    use ServiceTrait;

    /**
     * Get Office Address
     *
     * @param  string $countryCode ISO Country Code
     * @return string HTML
     */
    public function getOfficeAddress($countryCode)
    {
        $ret = '';
        if ($this->isApplicableCountryCode($countryCode)) {
            $ret .= '<address class="vcard"><p><strong class="fn org">Taylor &amp; Francis India</strong>';
            $ret .= '<br><span class="adr"><span class="street-address">2nd &amp; 3rd floor, The National Council of YMCAs of India, <br>1, Jai Singh Road</span>';
            $ret .= '<br><span class="locality">New Delhi</span>, <span class="postal-code">110 001</span>, <span class="country-name">IN</span></span>';
            $ret .= '<br>Telephone: <a href="tel:+91(11)43155100" class="tel">+91 (11) 4315 5100</a>';
            //$ret .= '<br>Email: <a href="mailto:inquiry@tandfindia.com" class="email">inquiry@tandfindia.com</a>';
            $ret .= '</p></address>';
        }
        return $ret;
    }

    /**
     * Get Customer Service Address
     *
     * @param  string $countryCode ISO Country Code
     * @return string HTML
     */
    public function getCustomerServiceAddress($countryCode)
    {
        $ret = '';
        if ($this->isApplicableCountryCode($countryCode)) {
            $ret .= '<address class="vcard"><p><strong class="fn org">T&amp;F India Showroom</strong>';
            $ret .= '<br><span class="adr"><span class="street-address">109 Basement, Prakash Mahal, Ansari Road, <br>Near Ansari Road Gurudwara</span>';
            $ret .= '<br><span class="locality">Daryaganj, Delhi</span>, <span class="postal-code">110 002</span>, <span class="country-name">IN</span></span>';
            $ret .= '<br>Telephone: <a href="tel:+91(11)43155109" class="tel">+91 (11) 4315 5109</a>, <a href="tel:+91(11)40155100" class="tel">+91 (11) 4015 5100</a>';
            $ret .= '<br>Email: <a href="mailto:inquiry@tandfindia.com" class="email">inquiry@tandfindia.com</a>';
            $ret .= '</p></address>';
        }
        return $ret;
    }

    /**
     * Get Customer Service Info
     *
     * @param  string $countryCode ISO Country Code
     * @return string HTML
     */
    public function getCustomerServiceInfo($countryCode)
    {
        $ret = '';
        if ($this->isApplicableCountryCode($countryCode)) {
            $ret .= 'Telephone: <a href="tel:+91(11)43155109" class="tel">+91 (11) 4315 5109</a>, <a href="tel:+91(11)40155100" class="tel">+91 (11) 4015 5100</a>';
            $ret .= '<br>Email: <a href="mailto:inquiry@tandfindia.com" class="email">inquiry@tandfindia.com</a>';
        }
        return $ret;
    }

    /**
     * Get Marketing Info
     *
     * @param  string $countryCode ISO Country Code
     * @return string HTML
     */
    public function getMarketingInfo($countryCode)
    {
        $ret = '';
        if ($this->isApplicableCountryCode($countryCode)) {
            $ret .= 'Telephone: <a href="tel:+91(11)43155174" class="tel">+91 (11) 4315 5174</a>, <a href="tel:+91(11)43155136" class="tel">+91 (11) 4315 5136</a>';
            $ret .= '<br>Email: <a href="mailto:marketing@tandfindia.com" class="email">marketing@tandfindia.com</a>';
        }
        return $ret;
    }

}