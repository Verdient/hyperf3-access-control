<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for access control.',
                    'source' => dirname(__DIR__) . '/publish/access_control.php',
                    'destination' => constant('BASE_PATH') . '/config/autoload/access_control.php',
                ]
            ]
        ];
    }
}
