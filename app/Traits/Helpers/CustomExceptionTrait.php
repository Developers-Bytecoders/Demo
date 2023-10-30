<?php

namespace App\Traits\Helpers;

use Exception;

trait CustomExceptionTrait
{
    /**
     * Lanza una excepción personalizada.
     *
     * @param  string  $message
     * @param  int  $code
     * @throws \Exception
     */
    public function throwCustomException(string $message, int $code = 400): void
    {
        throw new Exception($message, $code);
    }
}
