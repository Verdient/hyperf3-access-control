<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\ConfigInterface;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 凭据
 *
 * @author Verdient。
 */
class Credential
{
    /**
     * 认证信息
     *
     * @author Verdient。
     */
    protected IdentityInterface|null|false $identity = false;

    /**
     * 用户
     *
     * @author Verdient。
     */
    protected object|null|false $user = false;

    /**
     * 路由
     *
     * @author Verdient。
     */
    protected Route|null|false $route = false;

    /**
     * 容器
     *
     * @author Verdient。
     */
    protected ContainerInterface $container;

    /**
     * @param ServerRequestInterface $request 请求对象
     *
     * @author Verdient。
     */
    public function __construct(public readonly ServerRequestInterface $request)
    {
        $this->container = ApplicationContext::getContainer();
    }

    /**
     * 获取调度对象
     *
     * @author Verdient。
     */
    public function dispatched(): ?Dispatched
    {
        return $this->request->getAttribute(Dispatched::class);
    }

    /**
     * 获取服务器名称
     *
     * @author Verdient。
     */
    public function serverName(): ?string
    {
        return $this->dispatched()?->serverName;
    }

    /**
     * 获取是否是访客
     *
     * @author Verdient。
     */
    public function isGuest(): bool
    {
        return empty($this->user());
    }

    /**
     * 获取认证信息
     *
     * @author Verdient。
     */
    public function identity(): ?Identity
    {
        if ($this->identity === false) {

            $this->identity = null;

            if ($this->route()) {
                $config = $this->container->get(ConfigInterface::class);
                $authenticatorClass = $config
                    ->get('access_control.authenticators.' . $this->serverName(), AuthenticatorInterface::class);

                if ($this->container->has($authenticatorClass)) {
                    /** @var AuthenticatorInterface */
                    $authenticator = $this->container->get($authenticatorClass);
                    $this->identity = $authenticator->identity($this->request, $this->route()->group());
                }
            }
        }

        return $this->identity;
    }

    /**
     * 获取用户
     *
     * @author Verdient。
     */
    public function user(): ?object
    {
        if ($this->user === false) {

            $this->user = null;

            if ($identity = $this->identity()) {
                if ($this->container->has(UserFinderInterface::class)) {
                    /** @var UserFinderInterface */
                    $userFinder = $this->container->get(UserFinderInterface::class);
                    $this->user = $userFinder->findUser($identity);
                }
            }
        }

        return $this->user;
    }

    /**
     * 获取当前访问的路由
     *
     * @author Verdient。
     */
    public function route(): ?Route
    {
        if ($this->route === false) {

            $this->route = null;

            if ($dispatched = $this->dispatched()) {
                $this->route = RouteManager::toRoute($this->serverName(), $this->request->getMethod(), $dispatched->handler->route, $dispatched->handler);
            }
        }

        return $this->route;
    }
}
