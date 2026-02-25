<?php

declare(strict_types=1);

namespace Verdient\Hyperf3\AccessControl\Annotation;

use Attribute;
use Hyperf\Di\Annotation\AbstractAnnotation;

/**
 * 允许访客访问
 *
 * @author Verdient。
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Guest extends AbstractAnnotation {}
