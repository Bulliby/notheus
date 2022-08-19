<?php

namespace App\Exception;

use App\Interface\CustomExceptionInterface;

class CustomValidationException extends \Exception implements CustomExceptionInterface
{
}
