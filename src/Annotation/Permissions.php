<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * 访问权限
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Permissions extends AbstractAnnotation
{
    /**
     * @var array $permissions 需要的权限
     *
     * @author Verdient。
     */
    public readonly array $permissions;

    /**
     * 构造方法
     *
     * @param array $permissions 需要的权限
     *
     * @author Verdient。
     */
    public function __construct(string|array $permissions)
    {
        $this->permissions = is_string($permissions) ? [$permissions] : $permissions;
    }
}
