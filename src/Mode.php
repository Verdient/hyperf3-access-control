<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 认证方式
 * @author Verdient。
 */
enum Mode: string
{
    /**
     * 默认
     * @author Verdient。
     */
    case DEFAULT = 'DEFAULT';

    /**
     * 公开接口，不进行认证
     * @author Verdient。
     */
    case PUBLIC = 'PUBLIC';

    /**
     * 认证用户
     * @author Verdient。
     */
    case AUTHENTICATED = 'AUTHENTICATED';
}
