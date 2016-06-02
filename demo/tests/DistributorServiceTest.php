<?php

use TandF\India\Service\Distributor as DistributorService;

class DistributorServiceTest extends PHPUnit_Framework_TestCase
{

    protected $sampleDistributorId = '910T50';

    protected $sampleDistributorIds = ['910T50', '902A14', '902A21'];

    protected $distributorService;

    public function __construct()
    {
        parent::__construct();
        $this->distributorService = new DistributorService();
    }

    public function testAllDistributorsCanBeFound()
    {
        $distributors = $this->distributorService->all();
        // Assert
        $this->assertInternalType('array', $distributors);
        $this->assertNotEmpty($distributors);
        $this->assertGreaterThan(1, count($distributors));
    }

    public function testDistributorCanBeFound()
    {
        $distributor = $this->distributorService->find($this->sampleDistributorId);
        // Assert
        $this->assertInstanceOf('TandF\India\Entity\Distributor', $distributor);
        $this->assertObjectHasAttribute('id', $distributor);
        $this->assertAttributeEquals('910T50', 'id', $distributor);
    }

    public function testManyDistributorsCanBeFound()
    {
        $distributors = $this->distributorService->findAllById($this->sampleDistributorIds);
        // Assert
        $this->assertInternalType('array', $distributors);
        $this->assertNotEmpty($distributors);
        $this->assertLessThanOrEqual(3, count($distributors));
    }

    public function testListingOfDistributorsCanBeFound()
    {
        $distributors = $this->distributorService->getListingOfDistributorsById($this->sampleDistributorIds);
        // Assert
        $this->assertInternalType('string', $distributors);
        $this->assertNotEmpty($distributors);
        $this->assertStringStartsWith('<address class="vcard">', $distributors);
    }

}