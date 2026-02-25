<?php

use Verdient\Hyperf3\AccessControl\Mode;

use function Hyperf\Support\env;

return [
    'default_mode' => env('ACCESS_CONTROL_DEFAULT_MODE', Mode::AUTHENTICATED),
    'default_group' => env('ACCESS_CONTROL_DEFAULT_GROUP', 'default'),
    'default_modes' => [],
    'default_groups' => [],
    'authenticators' => []
];
