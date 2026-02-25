<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl\Facade;

use Verdient\Hyperf3\AccessControl\Credential;
use Verdient\Hyperf3\AccessControl\CredentialContext;
use Verdient\Hyperf3\AccessControl\Identity;

/**
 * 认证
 *
 * @author Verdient。
 */
class Auth
{
    /**
     * 获取当前认证的用户
     *
     * @author Verdient。
     */
    public static function user(): ?object
    {
        return CredentialContext::get()?->user();
    }

    /**
     * 获取当前认证的凭据
     *
     * @author Verdient。
     */
    public static function credential(): ?Credential
    {
        return CredentialContext::get();
    }

    /**
     * 获取当前的认证信息
     *
     * @author Verdient。
     */
    public static function identity(): ?Identity
    {
        return CredentialContext::get()?->identity();
    }
}
