<?php

return [
    'google_search_engine_id' => function_exists('env') ? env('GOOGLE_SEARCH_ENGINE_ID', '') : '',
    'google_search_api_key' => function_exists('env') ? env('GOOGLE_SEARCH_API_KEY', '') : ''
];
