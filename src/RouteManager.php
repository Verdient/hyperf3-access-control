<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use BackedEnum;
use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;
use Verdient\Hyperf3\AccessControl\Annotation\Group;
use Verdient\Hyperf3\AccessControl\Annotation\Guest;
use Verdient\Hyperf3\AccessControl\Annotation\Permissions;
use Verdient\Hyperf3\HttpAction\ActionManager;

use function Hyperf\Config\config;

/**
 * 路由管理器
 *
 * @author Verdient。
 */
class RouteManager
{
    /**
     * 路由集合
     *
     * @author Verdient。
     */
    protected static array $routes = [];

    /**
     * 获取路由
     *
     * @param string $serverName 服务器名称
     *
     * @return Route[]
     * @author Verdient。
     */
    public static function routes(string $serverName): array
    {
        if (!array_key_exists($serverName, static::$routes)) {
            static::$routes[$serverName] = [];
            if (ApplicationContext::hasContainer()) {
                /** @var DispatcherFactory */
                $dispatcherFactory = ApplicationContext::getContainer()
                    ->get(DispatcherFactory::class);
                $router = $dispatcherFactory->getRouter($serverName);

                [$staticRouters, $variableRouters] = $router->getData();

                foreach ($staticRouters as $requestMethod => $items) {
                    foreach ($items as $path => $handler) {
                        if ($route = static::toRoute($serverName, $requestMethod, $path, $handler)) {
                            static::$routes[$serverName][] = $route;
                        }
                    }
                }

                foreach ($variableRouters as $requestMethod => $items) {
                    foreach ($items as $path => $handler) {
                        foreach ($handler['routeMap'] as $map) {
                            if ($route = static::toRoute($serverName, $requestMethod, $path, $map[0])) {
                                static::$routes[$serverName][] = $route;
                            }
                        }
                    }
                }
            }
        }
        return static::$routes[$serverName];
    }

    /**
     * 获取所有可以访问的路由
     *
     * @param Credential $credential 凭证
     *
     * @return Route[]
     * @author Verdient。
     */
    public static function allows(Credential $credential)
    {
        $result = [];

        foreach (static::routes($credential->serverName()) as $route) {
            if (Guard::pass($credential, $route) === Result::PASS) {
                $result[] = $route;
            }
        }

        return $result;
    }

    /**
     * 解析请求处理器
     *
     * @param Handler $handler 请求处理器
     *
     * @author Verdient。
     */
    public static function parseHandler(Handler $handler): ?array
    {
        if (is_array($handler->callback)) {
            $service = $handler->callback[0];
            $action = $handler->callback[1];
            return [$service, $action];
        } else if (is_string($handler->callback)) {
            $callback = explode('@', $handler->callback);
            if (count($callback) === 2) {
                return $callback;
            }
        }
        return null;
    }

    /**
     * 将处理器转换为路由对象
     *
     * @param string $serverName 服务器名称
     * @param string $requestMethod 请求方法
     * @param string $path 路径
     * @param Handler $handler 处理器
     *
     * @author Verdient。
     */
    public static function toRoute(string $serverName, string $requestMethod, string $path, Handler $handler): ?Route
    {
        if (!$handler = static::parseHandler($handler)) {
            return null;
        }

        [$className, $methodName] = $handler;

        $classMethodAnnotations = AnnotationCollector::getClassMethodAnnotation($className, $methodName);

        $hasMatched = false;

        $permissions = [];

        $config = config('access_control');

        if (isset($config['default_modes'][$serverName])) {
            $defaultMode = $config['default_modes'][$serverName];
        } else {
            $defaultMode = config('access_control.default_mode', Mode::AUTHENTICATED->value);
        }

        if ($defaultMode instanceof BackedEnum) {
            $defaultMode = $defaultMode->value;
        }

        $allowGuest = $defaultMode === Mode::PUBLIC->value;

        if (isset($classMethodAnnotations[Permissions::class])) {
            $hasMatched = true;
            /** @var Permissions */
            $attribute = $classMethodAnnotations[Permissions::class];
            $permissions = $attribute->permissions;
        }

        if (isset($classMethodAnnotations[Guest::class])) {
            $hasMatched = true;
            $allowGuest = true;
        }

        if (!$hasMatched) {
            $classAnnotations = AnnotationCollector::getClassAnnotations($className);

            if (isset($classAnnotations[Permissions::class])) {
                /** @var Permissions */
                $attribute = $classAnnotations[Permissions::class];
                $permissions = $attribute->permissions;
            }

            if (isset($classAnnotations[Guest::class])) {
                $allowGuest = true;
            }
        }

        $group = null;

        if (isset($classMethodAnnotations[Group::class])) {
            /** @var Group */
            $attribute = $classMethodAnnotations[Group::class];
            $group = $attribute->group;
        }

        if ($group === null && isset($classAnnotations[Group::class])) {
            /** @var Group */
            $attribute = $classAnnotations[Group::class];
            $group = $attribute->group;
        }

        if ($group === null) {
            if ($action = ActionManager::get($className)) {
                foreach ($action->inheritedAttributes as $attributes) {
                    foreach ($attributes as $attribute) {
                        if ($attribute instanceof Group) {
                            $group = $attribute->group;
                            break;
                        }
                    }
                }
            }
        }

        return new Route(
            serverName: $serverName,
            method: $requestMethod,
            path: $path,
            className: $handler[0],
            methodName: $handler[1],
            allowGuest: $allowGuest,
            permissions: $permissions,
            group: $group ?: config('access_control.default_group') ?: 'default'
        );
    }
}
