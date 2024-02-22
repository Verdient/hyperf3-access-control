<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 认证信息接口
 * @author Verdient。
 */
interface IdentityInterface
{
    /**
     * 获取唯一标识
     * @author Verdient。
     */
    public function getIdentifier(): int|string;

    /**
     * 获取数据
     * @author Verdient。
     */
    public function getData(): array;
}
