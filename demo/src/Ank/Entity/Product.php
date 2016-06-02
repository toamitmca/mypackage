<?php
/**
 * Product
 *
 * @filesource
 */
namespace MyPackage\Ank\Entity;

/**
 * Encapsulates a Product.
 *
 * @package    MyPackage\Ank\Entity\Product
 * @version    1.0.1
 * @copyright  2016 Taylor & Francis Group
 * @author     Amit Kumar <toamitmca@gmail.com>
 */

class Product extends Entity implements \JsonSerializable
{

    /**
     * Load methods used across Entities.
     */
    use EntityTrait;

    /**
     * Load Constructor method.
     */
    use EntityConstructTrait;

    /**
     * @var string ISBN-13
     */
    protected $isbn;

    /**
     * @var string INR (Indian) ISBN-13
     */
    protected $inrIsbn;

    /**
     * @var string Format (PB/HB)
     */
    protected $format;

    /**
     * @var string ISO Currency Code (INR/GBP)
     */
    protected $currency;

    /**
     * @var string Price
     */
    protected $price;

    /**
     * @var string Distributor ID #1
     */
    protected $distributor1id;

    /**
     * @var string Distributor ID #2
     */
    protected $distributor2id;

    /**
     * @var string Distributor ID #3
     */
    protected $distributor3id;

    /**
     * @var string Division
     */
    protected $division;

    /**
     * @var string Subject
     */
    protected $subject;

    /**
     * @var string Status
     */
    protected $status;

    /**
     * Get All Distributor Identifiers for product
     *
     * @return array|null
     */
    public function getDistributorIds()
    {
        $distributorIds = [];
        if (isset($this->distributor1id) && ! empty($this->distributor1id)) {
            $distributorIds[] = $this->distributor1id;
        }
        if (isset($this->distributor2id) && ! empty($this->distributor2id)) {
            $distributorIds[] = $this->distributor2id;
        }
        if (isset($this->distributor3id) && ! empty($this->distributor3id)) {
            $distributorIds[] = $this->distributor3id;
        }

        return ( ! empty($distributorIds)) ? $distributorIds : null;
    }

    /**
     * Get INR (Indian) ISBN or ISBN
     *
     * @return string ISBN-13
     */
    public function getInrIsbnOrIsbn()
    {
        $isbn = (isset($this->inrIsbn) && ! empty($this->isbn)) ? $this->inrIsbn : $this->isbn;

        return $isbn;
    }

    /**
     * Format Info for HTML presentation
     *
     * @return string HTML
     */
    public function printFormatInfo()
    {
        switch (strtolower($this->format)) {
            case 'pb': $format = 'Paperback'; break;
            case 'hb': $format = 'Hardback'; break;
            default: $format = $this->format;
        }
        $str = '<div><strong>' . $format . '</strong>: <abbr title="ISBN: ' . $this->getInrIsbnOrIsbn() . '" class="smaller monospace">' . $this->getInrIsbnOrIsbn() . '</abbr></div>';

        return $str;
    }

    /**
     * Product Status Message for HTML presentation
     *
     * @return string HTML message block.
     */
    public function printStatusMessage()
    {
        switch (strtolower($this->status)) {
            case 'out of print':
                $str = '<em class="text-danger">Out-of-print</em>';
                break;
            case 'unavailable':
                $str = '<em class="text-danger">Unavailable</em>';
                break;
            case 'out of stock':
                $str = '<em class="text-warning">Currently out of stock</em>';
                break;
            case 'temporarily of stock':
                $str = '<em class="text-warning">Temporarily Out Of Stock</em>';
                break;
            default:
                //$str = $this->format;
                $str = null;
        }

        return ( ! empty($str)) ? '<div id="productFormatMessage">' . $str . '</div>' : '';
    }

    /**
     * Get Currency Symbol
     *
     * @return string Unicode character for currency
     */
    public function currencySymbol()
    {
        $str = '&pound;';
        if ($this->currency == 'INR') {
            // Correct display of INR symbol; ₹
            $str = '&#8377;';
        }

        return $str;
    }

    /**
     * Currency Symbol for HTML presentation
     *
     * @return string HTML
     */
    public function printCurrencySymbol()
    {
        return '<abbr title="' . $this->currency . '">' . $this->currencySymbol() . '</abbr>';
    }

    /**
     * Pricing for HTML presentation
     *
     * @return string HTML
     */
    public function printPrice()
    {
        $currencySymbol = $this->printCurrencySymbol();
        $price = substr($this->price, 0, -3) . '<sup style="font-size:62%;">' . substr($this->price, -3) . '</sup>';

        return $currencySymbol . $price;
    }

}