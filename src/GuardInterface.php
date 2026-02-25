<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 守卫接口
 *
 * @author Verdient。
 */
interface GuardInterface
{
    /**
     * 检查权限
     *
     * @author Verdient。
     */
    public function pass(Credential $credential, Route $route): Result;
}
