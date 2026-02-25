<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Psr\Http\Message\ServerRequestInterface;

/**
 * 认证器接口
 *
 * @author Verdient。
 */
interface AuthenticatorInterface
{
    /**
     * 获取认证信息
     *
     * @param ServerRequestInterface $request 请求对象
     * @param string $group 访问控制组
     *
     * @author Verdient。
     */
    public function identity(ServerRequestInterface $request, string $group): ?IdentityInterface;
}
