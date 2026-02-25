<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Override;

/**
 * 认证信息
 *
 * @author Verdient。
 */
class Identity implements IdentityInterface
{
    /**
     * @param int|string $identifier 标识符
     * @param array 数据
     *
     * @author Verdient。
     */
    public function __construct(
        protected int|string $identifier,
        protected array $data = []
    ) {}

    /**
     * @author Verdient。
     */
    #[Override]
    public function getIdentifier(): int|string
    {
        return $this->identifier;
    }

    /**
     * @author Verdient。
     */
    #[Override]
    public function getData(): array
    {
        return $this->data;
    }
}
