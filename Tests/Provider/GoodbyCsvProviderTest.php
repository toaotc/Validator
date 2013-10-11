<?php

namespace Toa\Component\Validator\Tests\Provider;

use Toa\Component\Validator\Provider\GoodbyCsvProvider;

/**
 * Class GoodbyCsvProviderTest
 *
 * @author Enrico Thies <enrico.thies@gmail.com>
 */
class GoodbyCsvProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $provider;
    protected $csv;

    protected function setUp()
    {
        if (!class_exists('Goodby\CSV\Import\Standard\Lexer')) {
            $this->markTestSkipped('The "GoodbyCsv" component is not available');
        }

        $this->provider = new GoodbyCsvProvider();

        $this->csv = __DIR__.'/../Constraints/Fixtures/test.csv';
    }

    /**
     * @test
     */
    public function testCountRowsIsValid()
    {
        $this->assertEquals(4, $this->provider->countRows($this->csv));
    }

    /**
     * @test
     */
    public function testcollectColumnSizesIsValid()
    {
        $columnSizes = $this->provider->collectColumnSizes($this->csv);

        $this->assertInternalType('array', $columnSizes);
        $this->assertSame($this->provider->collectColumnSizes($this->csv), $columnSizes);
        $this->assertCount(2, $columnSizes);
        $this->assertArrayHasKey(3, $columnSizes);
        $this->assertEquals(array(1, 2, 4), $columnSizes[3]);
        $this->assertArrayHasKey(1, $columnSizes);
        $this->assertEquals(array(3), $columnSizes[1]);
    }
}
