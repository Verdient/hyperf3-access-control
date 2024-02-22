<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 权限守卫接口
 * @author Verdient。
 */
interface PrivilegeGuardInterface
{
    /**
     * 检查权限
     * @author Verdient。
     */
    public function pass(Credential $credential, Route $route): bool;
}
