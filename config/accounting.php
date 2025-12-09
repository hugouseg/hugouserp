<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auto-Post Sales to General Ledger
    |--------------------------------------------------------------------------
    |
    | When enabled, sales transactions are automatically posted to GL.
    | When disabled, sales require finance approval before posting.
    |
    */
    'auto_post_sales_to_gl' => env('ACCOUNTING_AUTO_POST_SALES', true),

    /*
    |--------------------------------------------------------------------------
    | Auto-Post Purchases to General Ledger
    |--------------------------------------------------------------------------
    |
    | When enabled, purchase transactions are automatically posted to GL.
    | When disabled, purchases require finance approval before posting.
    |
    */
    'auto_post_purchases_to_gl' => env('ACCOUNTING_AUTO_POST_PURCHASES', true),

    /*
    |--------------------------------------------------------------------------
    | Fiscal Year Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for fiscal year management.
    |
    */
    'fiscal_year_start_month' => env('FISCAL_YEAR_START_MONTH', 1), // January
    'fiscal_year_start_day' => env('FISCAL_YEAR_START_DAY', 1),

    /*
    |--------------------------------------------------------------------------
    | Journal Entry Auto-numbering
    |--------------------------------------------------------------------------
    |
    | Configuration for automatic journal entry number generation.
    |
    */
    'journal_entry_prefix' => env('JOURNAL_ENTRY_PREFIX', 'JE-'),
    'journal_entry_padding' => env('JOURNAL_ENTRY_PADDING', 6),

    /*
    |--------------------------------------------------------------------------
    | Require Approval
    |--------------------------------------------------------------------------
    |
    | Require approval for certain accounting operations.
    |
    */
    'require_journal_approval' => env('ACCOUNTING_REQUIRE_JOURNAL_APPROVAL', false),
    'require_reconciliation_approval' => env('ACCOUNTING_REQUIRE_RECONCILIATION_APPROVAL', true),
];
