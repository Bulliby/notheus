<?php

namespace App\Exception;

use App\Interface\CustomExceptionInterface;

/**
 * Client application guess the Next primary ID for entities. This exception
 * is sent if there is a mismatch between front and back.
 */
class ClientEntityIdMismatch extends \Exception implements CustomExceptionInterface
{
}
