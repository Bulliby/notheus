<?php

namespace App\Const;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * The messages of the exceptioon who is injected in the log
 * with some other elements.
 */
class ErrorMessages
{
    public static function entityNoFound(int $id, string $class): string
    {
        return sprintf("Not found, try to fecth id : %d from %s ", $id, $class);
    }

    public static function validationMessage(
        ConstraintViolationListInterface $errors
    ): string
    {
        $messages = [];

        foreach($errors as $error) 
        {
            $messages[$error->getPropertyPath()] = $error->getMessage();
        }

        return json_encode($messages);
    }

    public static function clientEntityIdMismatch(int $idClient, int $serverId): string
    {
        return sprintf(
            "Client id guessed %d server autoincrement id : %d ", 
            $idClient, 
            $serverId
        );
    }
}
