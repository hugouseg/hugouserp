<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Maximum Line Discount Percentage
    |--------------------------------------------------------------------------
    |
    | The maximum discount percentage that can be applied to a single line item.
    |
    */
    'max_line_discount_percent' => env('MAX_LINE_DISCOUNT', 50),

    /*
    |--------------------------------------------------------------------------
    | Maximum Invoice Discount Percentage
    |--------------------------------------------------------------------------
    |
    | The maximum discount percentage that can be applied to the entire invoice.
    |
    */
    'max_invoice_discount_percent' => env('MAX_INVOICE_DISCOUNT', 30),

    /*
    |--------------------------------------------------------------------------
    | Invoice Auto-numbering
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic invoice number generation.
    |
    */
    'auto_number_prefix' => env('SALES_AUTO_NUMBER_PREFIX', 'INV-'),
    'auto_number_padding' => env('SALES_AUTO_NUMBER_PADDING', 6),

    /*
    |--------------------------------------------------------------------------
    | Default Tax Rate
    |--------------------------------------------------------------------------
    |
    | Default tax rate to apply to sales (percentage).
    |
    */
    'default_tax_rate' => env('SALES_DEFAULT_TAX_RATE', 0),

    /*
    |--------------------------------------------------------------------------
    | Require Customer
    |--------------------------------------------------------------------------
    |
    | Whether customer information is required for all sales.
    |
    */
    'require_customer' => env('SALES_REQUIRE_CUSTOMER', false),
];
