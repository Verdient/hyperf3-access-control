<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\Context\ApplicationContext;

/**
 * 路由
 * @author Verdient。
 */
class Route
{
    public function __construct(
        protected string $serverName,
        protected string $className,
        protected string $methodName,
        protected Mode $mode,
        protected string $group
    ) {
    }

    /**
     * 获取服务器名称
     * @author Verdient。
     */
    public function serverName(): string
    {
        return $this->serverName;
    }

    /**
     * 获取类名
     * @author Verdient。
     */
    public function className(): string
    {
        return $this->className;
    }

    /**
     * 获取方法名
     * @author Verdient。
     */
    public function methodName(): string
    {
        return $this->methodName;
    }

    /**
     * 获取访问控制模式
     * @author Verdient。
     */
    public function mode(): Mode
    {
        return $this->mode;
    }

    /**
     * 获取访问控制组
     * @author Verdient。
     */
    public function group(): string
    {
        return $this->group;
    }

    /**
     * 获取路由是否可以访问
     * @author Verdient。
     */
    public function pass(Credential $credential)
    {
        if ($credential->serverName() == $this->serverName) {
            switch ($this->mode) {
                case Mode::PUBLIC:
                    return Result::PASS;
                case Mode::AUTHENTICATED:
                    return $credential->isGuest() ? Result::UNAUTHORIZED : Result::PASS;
                case Mode::DEFAULT:
                    if ($credential->isGuest()) {
                        return Result::UNAUTHORIZED;
                    }
                    if (!ApplicationContext::hasContainer()) {
                        return Result::FORBIDDEN;
                    }
                    $container = ApplicationContext::getContainer();
                    if (!$container->has(PrivilegeGuardInterface::class)) {
                        return Result::FORBIDDEN;
                    }
                    /** @var PrivilegeGuardInterface */
                    $privilegeGuard = $container->get(PrivilegeGuardInterface::class);
                    return $privilegeGuard->pass($credential, $this) ? Result::PASS : Result::FORBIDDEN;
            }
        }
        return Result::FORBIDDEN;
    }
}
