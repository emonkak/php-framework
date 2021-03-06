<?php

namespace Emonkak\Waf\Controller;

use Emonkak\Di\Annotation\Inject;
use Symfony\Component\HttpFoundation\Request;

/**
 * Provides access to a request object.
 */
trait RequestAware
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Sets a request object.
     *
     * @Inject
     *
     * @param Request $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }
}
