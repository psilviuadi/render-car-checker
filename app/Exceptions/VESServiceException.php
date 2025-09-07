<?php

namespace App\Exceptions;

use Exception;

class VESServiceException extends Exception
{
    public ?array $apiResponse;

    public function __construct(string $message, ?array $apiResponse = null)
    {
        parent::__construct($message);
        $this->apiResponse = $apiResponse;
    }
}
