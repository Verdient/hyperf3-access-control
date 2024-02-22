<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

/**
 * 用户查找器接口
 * @author Verdient。
 */
interface UserFinderInterface
{
    /**
     * 查找用户
     * @author Verdient。
     */
    public function findUser(IdentityInterface $identity): ?object;
}
