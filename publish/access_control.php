<?php

use function Hyperf\Support\env;

return [
    'default_mode' => env('ACCESS_CONTROL_DEFAULT_MODE'),
    'default_group' => env('ACCESS_CONTROL_DEFAULT_GROUP'),
    'authenticators' => []
];
