<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Services\Interfaces\DataProviderServiceInterface;
use App\Services\MailerService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;
use Mockery;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    use WithFaker;

    private static $companyInfo;
    private static $historicalData;
    private static $dataProviderService;
    private static $companySymbolList;

    public static function setUpBeforeClass(): void
    {
        static::$companyInfo = json_decode(file_get_contents(__DIR__.'/../../tests/fixtures/company-info.json'), true);
        static::$companySymbolList = collect(static::$companyInfo)->pluck('Company Name', 'Symbol')->all();
        static::$historicalData = json_decode(file_get_contents(__DIR__.'/../../tests/fixtures/historical-data.json'), true);
    }

    protected function setUp(): void
    {
        parent::setUp();
        \Session::start();
        Mail::fake();
        $this->mockMethods();
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }

    private function mockMethods(): void
    {
        $this->mock(DataProviderServiceInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('getCompanyInfo')->andReturn(static::$companyInfo);
            $mock->shouldReceive('getHistoricalData')->andReturn(static::$historicalData);
        });
        $this->mock(MailerService::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('send');
        });
    }

    public function testHomePageReturnValidInfo(): void
    {
        $response = $this->get(route('report.index'));
        $response->assertStatus(Response::HTTP_OK)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('report/index')
                    ->where('companySymbolList', static::$companySymbolList)
            );
    }

    public function testFilterShouldReturnValidDataAndSendEmail(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAOI',
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-08',
            'email' => $this->faker->safeEmail(),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(Response::HTTP_OK)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('report/index')
                    ->where('companySymbolList', static::$companySymbolList)
                    ->where('historicalData', static::$historicalData)
            );
    }

    public function testFilterReturnsValidationErrorsWhenFormIsEmpty(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['company_symbol', 'start_date', 'end_date', 'email']);
    }

    public function testFilterReturnsValidationErrorsWhenCompanySymbolIsEmpty(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-08',
            'email' => $this->faker->safeEmail(),
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['company_symbol'])
            ->assertSessionDoesntHaveErrors(['start_date', 'end_date', 'email']);
    }

    public function testFilterReturnsValidationErrorsWhenCompanySymbolIsInvalid(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'ABCD',
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-08',
            'email' => $this->faker->safeEmail(),
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['company_symbol'])
            ->assertSessionDoesntHaveErrors(['start_date', 'end_date', 'email']);
    }

    public function testFilterReturnsValidationErrorsWhenEmailIsEmpty(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAIT',
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-08',
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['email'])
            ->assertSessionDoesntHaveErrors(['company_symbol', 'start_date', 'end_date']);
    }

    public function testFilterReturnsValidationErrorsWhenEmailIsInvalid(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAIT',
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-08',
            'email' => 'invalid-email-str',
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['email'])
            ->assertSessionDoesntHaveErrors(['company_symbol', 'start_date', 'end_date']);
    }

    public function testFilterReturnsValidationErrorsWhenStartDateIsGreaterThanEndDate(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAIT',
            'start_date' => '2023-04-07',
            'end_date' => '2023-04-05',
            'email' => $this->faker->safeEmail(),
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['start_date', 'end_date'])
            ->assertSessionDoesntHaveErrors(['company_symbol', 'email']);
    }

    public function testFilterWillNotReturnErrorWhenStartAndEndDateIsEqual(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAOI',
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-01',
            'email' => $this->faker->safeEmail(),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(Response::HTTP_OK)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('report/index')
                    ->where('companySymbolList', static::$companySymbolList)
                    ->where('historicalData', static::$historicalData)
            );
    }

    public function testWillNotBreakTemplateWhenHistoricalDataIsEmpty(): void
    {
        \Mockery::close();
        $this->mock(DataProviderServiceInterface::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('getCompanyInfo')->andReturn(static::$companyInfo);
            $mock->shouldReceive('getHistoricalData')->andReturn([]);
        });
        $this->mock(MailerService::class, function (Mockery\MockInterface $mock) {
            $mock->shouldReceive('send');
        });
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAOI',
            'start_date' => '2023-04-01',
            'end_date' => '2023-04-01',
            'email' => $this->faker->safeEmail(),
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertStatus(Response::HTTP_OK)
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('report/index')
                    ->where('companySymbolList', static::$companySymbolList)
                    ->where('historicalData', [])
            );
    }

    public function testFilterReturnsValidationErrorsWhenEndDateIsGreaterThanCurrentDate(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAIT',
            'start_date' => date('Y-m-d', strtotime('-1 days')),
            'end_date' => date('Y-m-d', strtotime('+1 days')),
            'email' => $this->faker->safeEmail(),
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['end_date'])
            ->assertSessionDoesntHaveErrors(['company_symbol', 'email']);
    }

    public function testFilterReturnsValidationErrorsWhenStartDateIsGreaterThanCurrentDate(): void
    {
        $response = $this->post('/', [
            '_token' => csrf_token(),
            'company_symbol' => 'AAIT',
            'start_date' => date('Y-m-d', strtotime('+1 days')),
            'end_date' => date('Y-m-d', strtotime('+1 days')),
            'email' => $this->faker->safeEmail(),
        ]);

        $response
            ->assertStatus(Response::HTTP_FOUND)
            ->assertSessionHasErrors(['start_date'])
            ->assertSessionDoesntHaveErrors(['company_symbol', 'email']);
    }
}
