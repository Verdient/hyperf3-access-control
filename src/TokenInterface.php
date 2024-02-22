<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 令牌接口
 * @author Verdient。
 */
interface TokenInterface
{
    /**
     * 获取令牌内容
     * @author Verdient。
     */
    public function getToken(): string;

    /**
     * 获取过期时间
     * @author Verdient。
     */
    public function getExpiredAt(): int;

    /**
     * 获取其他数据
     * @author Verdient。
     */
    public function getData(): array;
}
