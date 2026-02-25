<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 路由
 *
 * @author Verdient。
 */
class Route
{
    /**
     * @param string $serverName 服务器名称
     * @param string $method 方法
     * @param string $path 路径
     * @param string $className 类名
     * @param string $methodName 方法名
     * @param bool $allowGuest 是否允许访客访问
     * @param array $permissions 权限
     * @param string $group 组
     *
     * @author Verdient。
     */
    public function __construct(
        protected string $serverName,
        protected string $method,
        protected string $path,
        protected string $className,
        protected string $methodName,
        protected bool $allowGuest,
        protected array $permissions,
        protected string $group
    ) {}

    /**
     * 获取服务器名称
     *
     * @author Verdient。
     */
    public function serverName(): string
    {
        return $this->serverName;
    }


    /**
     * 获取方法
     *
     * @author Verdient。
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * 获取路径
     *
     * @author Verdient。
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * 获取类名
     *
     * @author Verdient。
     */
    public function className(): string
    {
        return $this->className;
    }

    /**
     * 获取方法名
     *
     * @author Verdient。
     */
    public function methodName(): string
    {
        return $this->methodName;
    }

    /**
     * 是否允许访客访问
     *
     * @author Verdient。
     */
    public function allowGuest(): bool
    {
        return $this->allowGuest;
    }

    /**
     * 路由所需的权限
     *
     * @author Verdient。
     */
    public function permissions(): array
    {
        return $this->permissions;
    }

    /**
     * 获取访问控制组
     *
     * @author Verdient。
     */
    public function group(): string
    {
        return $this->group;
    }
}
