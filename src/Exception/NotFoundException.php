<?php

namespace App\Exception;

use App\Interface\CraftedRequestException;

class NotFoundException extends \Exception implements CraftedRequestException
{
}
