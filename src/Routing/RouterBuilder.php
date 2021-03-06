<?php

namespace Emonkak\Waf\Routing;

use Symfony\Component\HttpFoundation\RequestMatcher;

/**
 * Provides the router instance factory.
 */
class RouterBuilder
{
    /**
     * @var RouterInterface[]
     */
    private $routers = [];

    /**
     * Adda any router.
     *
     * @param RouterInterface $router Any router instance.
     * @return RouterBuilder
     */
    public function add(RouterInterface $router)
    {
        $this->routers[] = $router;
        return $this;
    }

    /**
     * Adds a regexp router which will match the GET method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function get($pattern, $controller, $action)
    {
        return $this->method('GET', $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the POST method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function post($pattern, $controller, $action)
    {
        return $this->method('POST', $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the PATCH method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function patch($pattern, $controller, $action)
    {
        return $this->method('PATCH', $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the PUT method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function put($pattern, $controller, $action)
    {
        return $this->method('PUT', $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match the DELETE method.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function delete($pattern, $controller, $action)
    {
        return $this->method('DELETE', $pattern, $controller, $action);
    }

    /**
     * Adds a regexp router which will match any method.
     *
     * @param string $method     The method name for a request.
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function method($method, $pattern, $controller, $action)
    {
        $this->routers[] = new RequestMatcherRouter(
            new PatternRouter($pattern, $controller, $action),
            new RequestMatcher(null, null, $method)
        );
        return $this;
    }

    /**
     * Adds a regexp pattern router.
     *
     * @param string $pattern    The regexp pattern for a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @param string $action     The action name.
     * @return RouterBuilder
     */
    public function pattern($pattern, $controller, $action)
    {
        $this->routers[] = new PatternRouter($pattern, $controller, $action);
        return $this;
    }

    /**
     * Adds a resource router
     *
     * @param string $prefix     The prefix of a request path.
     * @param string $controller The fully qualified class name of the controller.
     * @return RouterBuilder
     */
    public function resource($prefix, $controller)
    {
        $this->routers[] = new ResourceRouter($prefix, $controller);
        return $this;
    }

    /**
     * Adds a namespace router.
     *
     * @param string $prefix    The prefix of a request path.
     * @param string $namespace The namespace for controller classes.
     * @return RouterBuilder
     */
    public function mount($prefix, $namespace)
    {
        $this->routers[] = new NamespaceRouter($prefix, $namespace);
        return $this;
    }

    /**
     * Builds a router collection.
     *
     * @return OptimizedRouterCollection
     */
    public function build()
    {
        return new RouterCollection($this->routers);
    }

    /**
     * Builds a optimized router collection.
     *
     * @return OptimizedRouterCollection
     */
    public function optimized()
    {
        return new OptimizedRouterCollection($this->routers);
    }
}
