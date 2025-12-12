<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\CurrencyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyServiceTest extends TestCase
{
    use RefreshDatabase;

    private CurrencyService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(CurrencyService::class);
    }

    /** @test */
    public function it_clears_cached_rates_when_updating_rates_for_same_day(): void
    {
        $date = now()->toDateString();

        $this->service->setRate('USD', 'EUR', 0.9, $date);
        $this->assertSame(0.9, $this->service->getRate('USD', 'EUR', $date));

        // Update the rate for the same day - cached entries should be cleared automatically
        $this->service->setRate('USD', 'EUR', 1.1, $date);

        $this->assertSame(1.1, $this->service->getRate('USD', 'EUR', $date));
    }

    /** @test */
    public function it_normalizes_date_inputs_for_rate_cache_keys(): void
    {
        $date = now()->toDateString();
        $datetime = $date.' 15:30:00';

        // Prime the cache using a datetime string
        $this->service->setRate('USD', 'GBP', 0.8, $date);
        $this->assertSame(0.8, $this->service->getRate('USD', 'GBP', $datetime));

        // Update the rate for the same calendar day; cache should be cleared for normalized key
        $this->service->setRate('USD', 'GBP', 0.85, $date);

        $this->assertSame(0.85, $this->service->getRate('USD', 'GBP', $datetime));
    }
}
