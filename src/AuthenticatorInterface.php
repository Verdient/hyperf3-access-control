<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 认证器接口
 * @author Verdient。
 */
interface AuthenticatorInterface
{
    /**
     * 获取是否通过认证
     * @author Verdient。
     */
    public function pass(Credential $credential): bool;

    /**
     * 获取认证信息
     * @author Verdient。
     */
    public function identity(Credential $credential): ?IdentityInterface;
}
