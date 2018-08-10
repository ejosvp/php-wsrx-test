<?php declare(strict_types=1);

namespace App\Rest\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 */
class Path
{
    /**
     * @var string
     * @Required
     */
    private $path;

    public function __construct(array $values)
    {
        $this->path = $values['path'] ?? $values['value'];
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
