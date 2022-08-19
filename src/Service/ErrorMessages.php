<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * The messages of the exceptioon who is injected in the log
 * with some other elements.
 */
class ErrorMessages
{
    public static function entityNoFound(int $id, string $class)
    {
        return sprintf("Not found, try to fecth id : %d from %s ", $id, $class);
    }

    public static function validationMessage(ConstraintViolationListInterface $errors)
    {
        $messages = [];

        foreach($errors as $error) 
        {
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($messages);
    }
}
