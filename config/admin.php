<?php

return [
    'session_idle_timeout' => (int) env('ADMIN_SESSION_IDLE_TIMEOUT', 120),
    'public_cache_ttl_minutes' => (int) env('PUBLIC_CACHE_TTL_MINUTES', 30),
];
