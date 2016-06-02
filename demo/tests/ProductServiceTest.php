<?php

use TandF\India\Service\Product as ProductService;

class ProductServiceTest extends PHPUnit_Framework_TestCase
{

    protected $productService;

    public function __construct()
    {
        parent::__construct();
        $this->productService = new ProductService();
    }

    public function testAllProductsCanBeFound()
    {
        $products = $this->productService->all();
        // Assert
        $this->assertInternalType('array', $products);
        $this->assertNotEmpty($products);
        $this->assertGreaterThan(1, count($products));
    }

    public function testProductCanBeFound()
    {
        $product = $this->productService->find('9780415111201');
        // Assert
        $this->assertInstanceOf('TandF\India\Entity\Product', $product);
        $this->assertObjectHasAttribute('isbn', $product);
        $this->assertAttributeEquals('9780415111201', 'isbn', $product);
    }

    public function testInrIsbnCanBeFound()
    {
        $product = $this->productService->findByInrIsbn('9781498771931');
        // Assert
        $this->assertInstanceOf('TandF\India\Entity\Product', $product);
        $this->assertObjectHasAttribute('isbn', $product);
        $this->assertAttributeEquals('9781420073805', 'isbn', $product);
    }

    public function testIsbnFromInrIsbnCanBeFound()
    {
        $isbn = $this->productService->getIsbnFromInrIsbn('9781498771931');
        // Assert
        $this->assertInternalType('string', $isbn);
        $this->assertEquals('9781420073805', $isbn);
    }

    public function testIsApplicableCountryCode()
    {
        $isApplicable = $this->productService->isApplicableCountryCode('IN');
        // Assert
        $this->assertInternalType('bool', $isApplicable);
        $this->assertTrue($isApplicable);
    }

    public function testIsNotApplicableCountryCode()
    {
        $isNotApplicable = $this->productService->isApplicableCountryCode('US');
        // Assert
        $this->assertInternalType('bool', $isNotApplicable);
        $this->assertFalse($isNotApplicable);
    }

    public function testIsIsbnRestricted()
    {
        $isRestricted = $this->productService->isIsbnRestricted('9781498771931', 'IN');
        // Assert
        $this->assertInternalType('bool', $isRestricted);
        $this->assertTrue($isRestricted);
    }

    public function testIsIsbnNotRestricted()
    {
        $isNotRestricted = $this->productService->isIsbnRestricted('9781498771931', 'US');
        // Assert
        $this->assertInternalType('bool', $isNotRestricted);
        $this->assertFalse($isNotRestricted);
    }

    public function testPurchaseOptionCanBeLoaded()
    {
        $str = $this->productService->getPurchaseOption('9781498771931', 'IN');
        // Assert
        $this->assertInternalType('string', $str);
        $this->assertStringStartsWith('<div class="purchaseOption', $str);
    }

    public function testCorrectDistributorIsAvailable()
    {
        $str = $this->productService->getPurchaseOption('9781498771931', 'IN');
        // Assert
        $this->assertInternalType('string', $str);
        $this->assertContains('Ane Books', $str);
    }

}