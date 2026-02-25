<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\Context\Context;

/**
 * 凭据上下文
 *
 * @author Verdient。
 */
class CredentialContext
{
    /**
     * 获取当前访问的凭据
     *
     * @author Verdient。
     */
    public static function get(): ?Credential
    {
        return Context::get(Credential::class);
    }

    /**
     * 设置当前访问的凭据
     *
     * @param Credential $credential 凭据
     *
     * @author Verdient。
     */
    public static function set(Credential $credential): void
    {
        Context::set(Credential::class, $credential);
    }
}
