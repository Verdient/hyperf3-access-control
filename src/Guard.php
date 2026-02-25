<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\Context\ApplicationContext;
use Hyperf\HttpServer\Router\Dispatched;

/**
 * 守卫
 *
 * @author Verdient。
 */
class Guard
{
    /**
     * 判断路由是否可以访问
     *
     * @param Dispatched $dispatched 路由调度对象
     * @param Credential $credential 凭据
     *
     * @author Verdient。
     */
    public static function pass(Credential $credential, Route $route): Result
    {
        if ($credential->serverName() !== $route->serverName()) {
            return Result::FORBIDDEN;
        }

        if (!ApplicationContext::hasContainer()) {
            return Result::FORBIDDEN;
        }

        $container = ApplicationContext::getContainer();

        if (!$container->has(GuardInterface::class)) {
            return Result::FORBIDDEN;
        }

        /** @var GuardInterface */
        $guard = $container->get(GuardInterface::class);

        return $guard->pass($credential, $route);
    }
}
