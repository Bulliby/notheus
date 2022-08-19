<?php

namespace App\Exception;

use App\Interface\CustomExceptionInterface;

class CustomNotFoundException extends \Exception implements CustomExceptionInterface
{
}
