<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Grace Period (Days)
    |--------------------------------------------------------------------------
    |
    | Number of days after due date before a rental is marked as overdue.
    | Any invoice late beyond (due_date + grace_days) will be flagged.
    |
    */
    'grace_days' => env('RENTAL_GRACE_DAYS', 5),

    /*
    |--------------------------------------------------------------------------
    | Late Fee Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for late fees on overdue rentals.
    |
    */
    'late_fee_enabled' => env('RENTAL_LATE_FEE_ENABLED', true),
    'late_fee_amount' => env('RENTAL_LATE_FEE_AMOUNT', 50),
    'late_fee_type' => env('RENTAL_LATE_FEE_TYPE', 'fixed'), // 'fixed' or 'percentage'

    /*
    |--------------------------------------------------------------------------
    | Reminder Notifications
    |--------------------------------------------------------------------------
    |
    | Number of days before due date to send reminder notifications.
    |
    */
    'reminder_days_before' => env('RENTAL_REMINDER_DAYS', 3),

    /*
    |--------------------------------------------------------------------------
    | Security Deposit
    |--------------------------------------------------------------------------
    |
    | Default security deposit settings for rentals.
    |
    */
    'security_deposit_percentage' => env('RENTAL_SECURITY_DEPOSIT_PERCENT', 20),
    'security_deposit_required' => env('RENTAL_SECURITY_DEPOSIT_REQUIRED', true),
];
