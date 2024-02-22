<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 抽象认证器
 * @author Verdient。
 */
abstract class AbstractAuthenticator implements AuthenticatorInterface
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function pass(Credential $credential): bool
    {
        return !empty($this->identity($credential));
    }
}
