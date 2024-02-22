<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * 访问控制
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class AccessControl extends AbstractAnnotation
{
    /**
     * @inheritdoc
     * @author Verdient。
     */
    public function __construct(
        public Mode $mode = Mode::DEFAULT,
        public string $group = 'default'
    ) {
    }
}
