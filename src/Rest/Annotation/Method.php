<?php declare(strict_types=1);

namespace App\Rest\Annotation;

interface Method
{
    public function getMethod(): string;
}
