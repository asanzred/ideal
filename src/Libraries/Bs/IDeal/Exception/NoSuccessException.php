<?php

namespace Smallworldfs\Ideal\Libraries\Bs\IDeal\Exception;

use Smallworldfs\Ideal\Libraries\Bs\IDeal\Response\Response;

class NoSuccessException extends IDealException
{
    protected $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
