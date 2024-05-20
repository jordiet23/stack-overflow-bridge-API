<?php

namespace App\Domain;

class Owner
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $displayName,
        public readonly ?string $profileLink
    ) {}
}