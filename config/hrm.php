<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Social Insurance Settings
    |--------------------------------------------------------------------------
    |
    | Configure social insurance calculation parameters including the rate
    | and maximum salary subject to social insurance.
    |
    */
    'social_insurance' => [
        'rate' => env('HRM_SOCIAL_INSURANCE_RATE', 0.14),
        'max_salary' => env('HRM_SOCIAL_INSURANCE_MAX', 12600),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Brackets
    |--------------------------------------------------------------------------
    |
    | Define progressive tax brackets for income tax calculation.
    | Each bracket specifies a limit and the tax rate applicable to that bracket.
    |
    */
    'tax_brackets' => [
        ['limit' => 40000, 'rate' => 0],
        ['limit' => 55000, 'rate' => 0.10],
        ['limit' => 70000, 'rate' => 0.15],
        ['limit' => 200000, 'rate' => 0.20],
        ['limit' => 400000, 'rate' => 0.225],
        ['limit' => PHP_FLOAT_MAX, 'rate' => 0.25],
    ],
];
