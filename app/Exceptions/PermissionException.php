<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionException extends HttpException
{
    public function __construct(int $statusCode = 403, string $message = 'This route is forbidden for you', \Throwable $previous = null, array $headers = [], int $code = 0)
    {
        parent::__construct($statusCode, $message);
    }
}
