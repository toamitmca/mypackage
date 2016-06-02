<?php
/**
 * Product
 *
 * @filesource
 */
namespace MyPackage\Ank\Service;

use MyPackage\Ank\Entity\Product as ProductEntity;

/**
 * Service for providing Distributor data.
 *
 * @package    MyPackage\Ank\Service\Product
 * @version    1.0.1
 * @copyright  2016 Taylor & Francis Group
 * @author     Amit Kumar <toamitmca@gmail.com>
 */

class Product extends Service
{

    /**
     * Load methods used across Services.
     */
    use ServiceTrait;

    /**
     * @var array Products
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $products = file_get_contents(__DIR__ . '/../../data/products.json');
        $this->products = json_decode($products);
        // Load service and configuration
        parent::__construct();
    }
	public function getMyName(){
		return 'amit kumar';
	}
    /**
     * Get all products.
     *
     * @return array
     */
    public function all()
    {
        $ret = array();
        if (is_array($this->products) && ! empty($this->products)) {
            foreach ($this->products as $product) {
                if (is_object($product)) {
                    $ret[] = new ProductEntity((array) $product);
                }
            }
        }

        return $ret;
    }

    /**
     * Find a product.
     *
     * @param  int|string|array $param Identifier, name, or key-value pair.
     * @return null|ProductEntity
     */
    public function find($param)
    {
        if (is_string($param)) {
            return $this->findByIsbn($param);
        } elseif (is_array($param)) {
            foreach ($param as $k => $v) {
                return $this->findByKey($k, $v);
            }
        }

        return null;
    }

    /**
     * Find a product by property key and matching value.
     *
     * @param  string $key Property Key
     * @param  mixed  $val Property Value
     * @return null|ProductEntity
     */
    public function findByKey($key, $val)
    {
        if (
            is_string($key) && ! empty($val)
            && is_array($this->products) && ! empty($this->products)
        ) {
            foreach ($this->products as $product) {
                if (
                    is_object($product) && isset($product->$key)
                    && $product->$key == $val
                ) {
                    return new ProductEntity((array) $product);
                }
            }
        }

        return null;
    }

    /**
     * Find a product by ISBN.
     *
     * @param  string $isbn ISBN-13
     * @return null|ProductEntity
     */
    public function findByIsbn($isbn)
    {
        return $this->findByKey('isbn', $isbn);
    }

    /**
     * Find a product by INR (Indian) ISBN.
     *
     * @param  string $isbn ISBN-13
     * @return null|ProductEntity
     */
    public function findByInrIsbn($isbn)
    {
        return $this->findByKey('inrIsbn', $isbn);
    }

    /**
     * Find a product by INR (Indian) ISBN or ISBN-13.
     *
     * @param  string $isbn ISBN-13
     * @return null|ProductEntity
     */
    public function findByInrIsbnOrIsbn($isbn)
    {
        if ($product = $this->findByInrIsbn($isbn)) {
            return $product;
        } elseif ($product = $this->findByIsbn($isbn)) {
            return $product;
        }

        return null;
    }

    /**
     * Get the INR (Indian) ISBN
     *
     * @param  string $isbn ISBN-13
     * @return string ISBN-13
     */
    public function getInrIsbn($isbn)
    {
        $product = $this->findByInrIsbn($isbn);

        return (is_object($product) && isset($product->inrIsbn)) ? $product->inrIsbn : null;
    }

    /**
     * Is the ISBN Restricted
     *
     * @param  string $isbn ISBN-13
     * @param  string $countryCode ISO Country Code
     * @return string ISBN-13
     */
    public function isIsbnRestricted($isbn, $countryCode = null)
    {
        // If user is not located in supported country, return false.
        if ($this->isApplicableCountryCode($countryCode)) {
            // Find product using ISBN-13 or INR (India) ISBN.
            if ($product = $this->findByInrIsbnOrIsbn($isbn)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get ISBN From INR (Indian) ISBN
     *
     * @param  string $isbn ISBN-13
     * @return string ISBN-13
     */
    public function getIsbnFromInrIsbn($isbn)
    {
        $product = $this->findByInrIsbn($isbn);

        return (is_object($product) && isset($product->isbn)) ? $product->isbn : $isbn;
    }

    /**
     * Get INR (Indian) ISBN From ISBN
     *
     * @param  string $isbn ISBN-13
     * @return string ISBN-13
     */
    public function getInrIsbnFromIsbn($isbn)
    {
        $product = $this->findByIsbn($isbn);

        return (is_object($product) && isset($product->inrIsbn)) ? $product->inrIsbn : $isbn;
    }

    /**
     * Get Purchase Option
     *
     * @param  string $isbn ISBN-13
     * @param  string $countryCode ISO Country Code
     * @return string|null HTML
     */
    public function getPurchaseOption($isbn, $countryCode = null, $ip = null)
    {
        $str = null;
        // If user is not located in supported country, return null.
        if ($this->isApplicableCountryCode($countryCode)) {
            if ($product = $this->findByInrIsbnOrIsbn($isbn)) {
                // Markup gets compiled here...
                // Start purchase-option block, with textual class/style based on product criteria.
                $str = '<div class="purchaseOption tfi-only" style="background-color:#fff;border:1px solid #fc3;">';
                // Show (disabled) select block.
                $str .= '<div class="buySelect hidden-print"></div>';
                // Show info block.
                $str .= '<div class="buyInfo">';
                $str .= $product->printFormatInfo();
                //$str .= $product->printStatusMessage();
                $str .= '</div>';
                // Show price/qty block.
                $str .= '<div class="purchasePrice"><div class="item-price">' . $product->printPrice() . '</div></div>';
                // Show purchase-warning block.
                $str .= '<div class="purchaseWarning">This product is only available for purchase in ' . $this->printApplicableCountryNames() . '.</div>';
                // We want to find Distributors related to this ISBN,
                // and present them to customer as an option to purchase.
                $str .= $this->getDistributorModal($product);
                // End purchase-option block.
                $str .= '</div>';
            }
        }

        return $str;
    }

    /**
     * Get TFI Distributor Modal
     *
     * @param  string $product ISBN-13
     * @return string|null HTML
     */
    public function getDistributorModal($product)
    {
        $str = null;
        if (is_object($product) && ($product instanceof ProductEntity)) {
            // Button trigger modal
            $str = '<div style="clear:both;text-align:center" class="tfi-actions"><button type="button" class="btn btn-default" data-toggle="modal" data-target="#inrDistModal">Purchase Locally</button></div>';
            // Modal
            $str .= '<div class="modal fade" id="inrDistModal" tabindex="-1" role="dialog" aria-labelledby="inrDistModalLabel"><div class="modal-dialog" role="document"><div class="modal-content">';
            $str .= '<div class="modal-header">';
            $str .= '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
            $str .= '<h4 class="modal-title" id="inrDistModalLabel">Contact information for your local distributors</h4>';
            $str .= '</div>';
            $str .= '<div class="modal-body">';
            // Distributors get listed here...
            $str .= $this->getDistributorOptions($product);
            // End model-body.
            $str .= '</div>';
            // End model-content, model-dialog and model.
            $str .= '</div></div></div>';
        }

        return $str;
    }

    /**
     * Get TFI Distributor Options
     *
     * @param  string $product ISBN-13
     * @return string|null HTML
     */
    public function getDistributorOptions($product)
    {
        $str = null;
        if (is_object($product) && ($product instanceof ProductEntity)) {
            $Dist = new Distributor();
            $str = $Dist->getListingOfDistributorsById($product->getDistributorIds());
        }

        return $str;
    }

}