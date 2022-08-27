<?php

namespace App\Service;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Interface\SerializationContextInterface;

//TODO Remove this class
class SerializationContext implements SerializationContextInterface
{
    private $_context = [];

    const IGNORE = ['id'];

    public function __construct()
    {
        $this->_context = [
            AbstractNormalizer::IGNORED_ATTRIBUTES => self::IGNORE,
        ];
    }
        
    public function getContext(): array
    {
        return $this->_context; 
    }

    public function addObjectToPopulate(object $toPopulate): void
    {
        $this->_context += [
            AbstractNormalizer::OBJECT_TO_POPULATE => $toPopulate
        ];
    }
}
