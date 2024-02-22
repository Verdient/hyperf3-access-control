<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 认证结果
 * @author Verdient。
 */
enum Result: string
{
    /**
     * 认证通过
     * @author Verdient。
     */
    case PASS = 'PASS';

    /**
     * 未授权
     * @author Verdient。
     */
    case UNAUTHORIZED = 'UNAUTHORIZED';

    /**
     * 禁止
     * @author Verdient。
     */
    case FORBIDDEN = 'FORBIDDEN';
}
