<?php

namespace Emonkak\Framework\Action;

use Emonkak\Framework\Exception\HttpNotFoundException;
use Emonkak\Framework\Routing\MatchedRoute;
use Emonkak\Framework\Utils\ReflectionUtils;
use Emonkak\Framework\Utils\StringUtils;
use Symfony\Component\HttpFoundation\Request;

class ActionDispatcher implements ActionDispatcherInterface
{
    /**
     * {@inheritDoc}
     */
    public function dispatch(Request $request, MatchedRoute $match, $controller)
    {
        $controllerReflection = $match->controller;
        $actionName = $this->getActionName($request, $match->action);

        try {
            $action = ReflectionUtils::getMethod($controllerReflection, $actionName);
        } catch (\ReflectionException $_) {
            throw new HttpNotFoundException(sprintf(
                'Controller method "%s::%s()" can not be found.',
                $controllerReflection->getName(),
                $actionName
            ));
        }

        if (!ReflectionUtils::matchesNumberOfArguments($action, count($match->params))) {
            throw new HttpNotFoundException(sprintf(
                'Number of arguments does not match to definition in the controller method "%s::%s()".',
                $controllerReflection->getName(),
                $actionName
            ));
        }

        return $action->invokeArgs($controller, $match->params);
    }

    /**
     * {@inheritDoc}
     */
    public function canDispatch(Request $request, MatchedRoute $match, $controller)
    {
        return true;
    }

    /**
     * @param Request $request
     * @param string $name
     * @return string
     */
    protected function getActionName(Request $request, $name)
    {
        return strtolower($request->getMethod()) . StringUtils::camelize($name);
    }
}
