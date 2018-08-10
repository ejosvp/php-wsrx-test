<?php declare(strict_types=1);

namespace App\Rest\Routing;

use App\Rest\Annotation\Method;
use App\Rest\Annotation\Path;
use App\Rest\Annotation\QueryParam;
use App\Rest\ServiceInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class RestLoader extends Loader
{
    private $reader;
    private $classes;

    private const SLASH = '/';

    public function __construct(Reader $annotationReader)
    {
        $this->reader = $annotationReader;
        $this->classes = [];
    }

    public function addService(ServiceInterface $service)
    {
        $this->classes[] = new \ReflectionClass(get_class($service));
    }

    public function load($resource, $type = null)
    {
        $collection = new RouteCollection();

        foreach ($this->classes as $service) {
            $collection->addCollection($this->readServiceRoutes($service));
        }

        dd($collection);

        return $collection;
    }

    private function readServiceRoutes(\ReflectionClass $class): RouteCollection
    {
        $prefix = '';

        $collection = new RouteCollection();
        $collection->addResource(new FileResource($class->getFileName()));

        foreach ($this->reader->getClassAnnotations($class) as $annotation) {
            if ($annotation instanceof Path) {
                $prefix = trim($annotation->getPath(), self::SLASH);
            }
        }

        foreach ($class->getMethods() as $method) {

            $path = lcfirst($method->getName());
            $defaults = ['_format' => 'json'];
            $requirements = [];
            $methods = [];

            foreach ($this->reader->getMethodAnnotations($method) as $annotation) {
                if ($annotation instanceof Method) {
                    $methods[] = $annotation->getMethod();
                } elseif ($annotation instanceof Path) {
                    $path = trim($annotation->getPath(), self::SLASH);
                } elseif ($annotation instanceof QueryParam) {
                    $requirements[$annotation->getName()] = $annotation->getRequirement();
                }
            }

            $path = implode(self::SLASH, [$prefix, $path]);
            $defaults['_controller'] = $class->getName() . '::' . $method->getName();
            $methods = !empty($methods) ? $methods : 'GET';

            $route = new Route($path, $defaults, $requirements, [], '', [], $methods);
            $name = strtolower(implode("_", [
                $class->getShortName(),
                $method->getShortName(),
            ]));

            $collection->add($name, $route);
        }

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'rest' === $type;
    }
}
