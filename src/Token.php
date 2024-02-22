<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 令牌
 * @author Verdient。
 */
class Token implements TokenInterface
{
    /**
     * @param string $token 令牌内容
     * @param int $expiredAt 过期时间
     * @param array $data 其他数据
     * @author Verdient。
     */
    public function __construct(
        protected string $token,
        protected int $expiredAt,
        protected array $data = []
    ) {
    }

    /**
     * 获取令牌内容
     * @author Verdient。
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * 获取过期时间
     * @author Verdient。
     */
    public function getExpiredAt(): int
    {
        return $this->expiredAt;
    }

    /**
     * 获取其他数据
     * @author Verdient。
     */
    public function getData(): array
    {
        return $this->data;
    }
}
