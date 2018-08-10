<?php declare(strict_types=1);

namespace App\Rest\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class Get implements Method
{
    public function getMethod(): string
    {
        return "GET";
    }
}
