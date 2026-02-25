<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * 授权组
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Group extends AbstractAnnotation
{
    /**
     * 构造方法
     *
     * @param array $group 授权组
     *
     * @author Verdient。
     */
    public function __construct(public readonly string $group) {}
}
