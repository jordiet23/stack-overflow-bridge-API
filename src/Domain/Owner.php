<?php

namespace App\Domain;

class Owner
{
    public function __construct(
        public int $id,
        public string $displayName,
        public string $profileLink
    ) {}
}