<?php

namespace App\Interface;

interface SerializationContextInterface
{
    public function getContext(): array;
    public function addObjectToPopulate(object $toPopulate): void;
}
