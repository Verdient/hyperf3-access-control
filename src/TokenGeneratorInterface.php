<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 令牌生成器接口
 * @author Verdient。
 */
interface TokenGeneratorInterface
{
    /**
     * 生成凭据
     * @param int|string $identifier 标识符
     * @param string $group 分组
     * @author Verdient。
     */
    public function generate(int|string $identifier, string $group = 'default'): TokenInterface;
}
