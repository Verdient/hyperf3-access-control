<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 凭据
 * @author Verdient。
 */
class Credential
{
    /**
     * 数据
     * @author Verdient。
     */
    protected array $data = [];

    /**
     * 容器
     * @author Verdient。
     */
    protected ContainerInterface $container;

    /**
     * @param ServerRequestInterface $request 请求对象
     * @author Verdient。
     */
    public function __construct(
        protected ServerRequestInterface $request
    ) {
        $this->container = ApplicationContext::getContainer();
    }

    /**
     * 获取请求对象
     * @author Verdient。
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

    /**
     * 获取分派对象
     * @author Verdient。
     */
    public function dispatched(): ?Dispatched
    {
        if (!array_key_exists('dispatched', $this->data)) {
            $this->data['dispatched'] = $this->request->getAttribute(Dispatched::class);
        }
        return $this->data['dispatched'];
    }

    /**
     * 获取服务器名称
     * @author Verdient。
     */
    public function serverName(): ?string
    {
        if (!array_key_exists('serverName', $this->data)) {
            $this->data['serverName'] = null;
            if ($dispatched = $this->dispatched()) {
                $this->data['serverName'] = $dispatched->serverName;
            }
        }
        return $this->data['serverName'];
    }

    /**
     * 获取是否是访客
     * @author Verdient。
     */
    public function isGuest(): bool
    {
        return empty($this->user());
    }

    /**
     * 获取认证信息
     * @author Verdient。
     */
    public function identity(): ?Identity
    {
        if (!array_key_exists('identity', $this->data)) {
            $this->data['identity'] = null;
            $config = $this->container->get(ConfigInterface::class);

            $authenticatorClass = $config
                ->get('access_control.authenticators.' . $this->serverName(), AuthenticatorInterface::class);

            if ($this->container->has($authenticatorClass)) {
                /** @var AuthenticatorInterface */
                $authenticator = $this->container->get($authenticatorClass);
                $this->data['identity'] = $authenticator->identity($this);
            }
        }
        return $this->data['identity'];
    }

    /**
     * 获取用户
     * @author Verdient。
     */
    public function user(): ?object
    {
        if (!array_key_exists('user', $this->data)) {
            $this->data['user'] = null;
            if ($identity = $this->identity()) {
                if ($this->container->has(UserFinderInterface::class)) {
                    /** @var UserFinderInterface */
                    $userFinder = $this->container->get(UserFinderInterface::class);
                    $this->data['user'] = $userFinder->findUser($identity);
                }
            }
        }
        return $this->data['user'];
    }

    /**
     * 获取当前访问的路由
     * @author Verdient。
     */
    public function route(): ?Route
    {
        if (!array_key_exists('route', $this->data)) {
            $this->data['route'] = null;
            if ($dispatched = $this->dispatched()) {
                $this->data['route'] = RouteManager::toRoute($this->serverName(), $dispatched->handler);
            } else {
                $this->data['route'] = null;
            }
        }
        return $this->data['route'];
    }

    /**
     * 是否允许访问
     * @author Verdient。
     */
    public function pass(): Result
    {
        if (!array_key_exists('pass', $this->data)) {
            if (!$route = $this->route()) {
                $this->data['pass'] = Result::PASS;
            } else {
                $this->data['pass'] = $route->pass($this);
            }
        }
        return $this->data['pass'];
    }
}
