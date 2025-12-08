<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Require POS Session
    |--------------------------------------------------------------------------
    |
    | When enabled, POS checkout requires an active session. Set to false
    | to allow sales without session tracking (not recommended for production).
    |
    */
    'require_session' => env('POS_REQUIRE_SESSION', true),
];
