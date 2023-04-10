<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\DataProviderService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DataProviderServiceTest extends TestCase
{
    private static $companyInfo;
    private static $historicalData;
    private static $dataProviderService;

    public static function setUpBeforeClass(): void
    {
        static::mockConfig();
        static::$companyInfo = json_decode(file_get_contents(__DIR__.'/../../tests/fixtures/company-info.json'), true);
        static::$historicalData = json_decode(file_get_contents(__DIR__.'/../../tests/fixtures/historical-data.json'), true);
        static::$dataProviderService = new DataProviderService();
    }

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }

    private static function mockConfig(): void
    {
        Config::shouldReceive('get')->with('common.rapid_api_key')->andReturn('asasjhjhj8787878kjkjk');
        Config::shouldReceive('get')->with('common.rapid_api_host')->andReturn('1ssds22323');
    }

    public function testShouldReturnValidCompanyInfo(): void
    {
        $mockedResponse = \Mockery::mock('overload:'.Http::class);
        $mockedResponse->shouldReceive('get->ok')->once()->andReturn(true);
        $mockedResponse->shouldReceive('get->ok->json')->once()->andReturn(static::$companyInfo);

        $companyInfo = static::$dataProviderService->getCompanyInfo();
        $this->assertIsArray($companyInfo);
        $this->assertNotEmpty($companyInfo);
        $this->assertNotEmpty($companyInfo[0]);
        $this->assertArrayHasKey('Symbol', $companyInfo[0]);
    }

    public function testCompanyInfoShouldReturnArray(): void
    {
        $mockedResponse = \Mockery::mock('overload:'.Http::class);
        $mockedResponse->shouldReceive('get->ok')->once()->andReturn(true);
        $mockedResponse->shouldReceive('get->ok->json')->once()->andReturn(static::$companyInfo);

        $companyInfo = static::$dataProviderService->getCompanyInfo();
        $this->assertIsArray($companyInfo);
        $this->assertNotEmpty($companyInfo);
    }

    public function testWhenCompanyInfoNotFoundItShouldThrowException(): void
    {
        $this->expectException(\Exception::class);
        $mockedResponse = \Mockery::mock('overload:'.Http::class);
        $mockedResponse->shouldReceive('get->ok')->once()->andReturn(false);
        static::$dataProviderService->getCompanyInfo();
    }

    public function testShouldReturnHistoricalData(): void
    {
        $mockedResponse = \Mockery::mock('overload:'.Http::class);
        $mockedResponse->shouldReceive('withHeaders->get->ok')->once()->andReturn(true);
        $mockedResponse->shouldReceive('withHeaders->get->ok->json')->once()->andReturn(static::$historicalData);

        $historicalData = static::$dataProviderService->getHistoricalData('AAIT', '2023-03-07', '2023-04-09');
        $this->assertIsArray($historicalData);
        $this->assertNotEmpty($historicalData);
        $this->assertNotEmpty($historicalData[0]);

        $expectedKeys = ['date', 'open', 'high', 'low', 'close', 'volume', 'adjclose'];
        $actualKeys = array_keys($historicalData[0]);
        $this->assertEquals($expectedKeys, $actualKeys);
    }

    public function testWhenHistoricalDataNotFoundItShouldThrowException(): void
    {
        $this->expectException(\Exception::class);
        $mockedResponse = \Mockery::mock('overload:'.Http::class);
        $mockedResponse->shouldReceive('withHeaders->get->ok')->once()->andReturn(false);

        static::$dataProviderService->getHistoricalData('AAIT', '2023-03-07', '2023-04-09');
    }
}
