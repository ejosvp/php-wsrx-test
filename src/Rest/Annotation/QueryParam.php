<?php declare(strict_types=1);

namespace App\Rest\Annotation;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Annotation\Target;
use Doctrine\Common\Annotations\Annotation\Required;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class QueryParam
{
    /**
     * @var string
     * @Required
     */
    private $name;

    /**
     * @var string
     */
    private $requirement;

    public function __construct(array $values)
    {
        $this->name = $values['name'] ?? $values['value'];
        $this->requirement = $values['requirement'] ?? null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRequirement(): ?string
    {
        return $this->requirement;
    }
}
