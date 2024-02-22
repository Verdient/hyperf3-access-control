<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\Context\ApplicationContext;
use Hyperf\Di\Annotation\AnnotationCollector;
use Hyperf\HttpServer\Router\DispatcherFactory;
use Hyperf\HttpServer\Router\Handler;

use function Hyperf\Config\config;

/**
 * 路由管理器
 * @author Verdient。
 */
class RouteManager
{
    /**
     * 路由集合
     * @author Verdient。
     */
    protected static array $routes = [];

    /**
     * 获取路由
     * @param string $serverName 服务器名称
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

                foreach ($staticRouters as $items) {
                    foreach ($items as $handler) {
                        if ($route = static::toRoute($serverName, $handler)) {
                            static::$routes[$serverName][] = $route;
                        }
                    }
                }

                foreach ($variableRouters as $items) {
                    foreach ($items as $handler) {
                        foreach ($handler['routeMap'] as $map) {
                            if ($route = static::toRoute($serverName, $map[0])) {
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
     * @return Route[]
     * @author Verdient。
     */
    public static function allows(Credential $credential)
    {
        $result = [];
        foreach (static::routes($credential->serverName()) as $route) {
            if ($route->pass($credential) === Result::PASS) {
                $result[] = $route;
            }
        }
        return $result;
    }

    /**
     * 解析请求处理器
     * @param Handler $handler 请求处理器
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
     * @param string $serverName 服务器名称
     * @param Handler $handler 处理器
     * @author Verdient。
     */
    public static function toRoute(string $serverName, Handler $handler): ?Route
    {
        if (!$handler = static::parseHandler($handler)) {
            return null;
        }

        [$className, $methodName] = $handler;

        $mode = config('access_control.default_mode', Mode::DEFAULT);

        $group = config('access_control.default_group', 'default');

        $annotations = AnnotationCollector::getClassMethodAnnotation($className, $methodName);

        $annotation = $annotations[AccessControl::class] ?? AnnotationCollector::getClassAnnotation($className, AccessControl::class);

        if ($annotation) {
            $mode = $annotation->mode;
            $group = $annotation->group;
        }

        return new Route(
            $serverName,
            $handler[0],
            $handler[1],
            $mode,
            $group
        );
    }
}
