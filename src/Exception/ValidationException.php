<?php

namespace App\Exception;

use App\Interface\CraftedRequestException;

class ValidationException extends \Exception implements CraftedRequestException
{
}
