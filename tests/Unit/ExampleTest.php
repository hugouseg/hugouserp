<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Test money helper formats USD correctly.
     */
    public function test_money_formats_usd_correctly(): void
    {
        $this->assertEquals('1,234.50 USD', money(1234.5, 'USD'));
    }

    /**
     * Test money helper formats zero amount correctly.
     */
    public function test_money_formats_zero_correctly(): void
    {
        $this->assertEquals('0.00 EGP', money(0, 'EGP'));
    }

    /**
     * Test money helper rounds to two decimal places.
     */
    public function test_money_rounds_to_two_decimals(): void
    {
        $this->assertEquals('9,999.99 EUR', money(9999.987, 'EUR'));
    }
}
