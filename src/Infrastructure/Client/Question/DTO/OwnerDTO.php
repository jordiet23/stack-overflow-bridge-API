<?php

namespace App\Infrastructure\Client\Question\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class OwnerDTO
{
    public function __construct(
        #[SerializedName('account_id')] public readonly ?int $accountId,
        public readonly ?int $reputation,
        #[SerializedName('user_id')] public readonly ?int $userId,
        #[SerializedName('user_type')] public readonly ?string $userType,
        #[SerializedName('profile_image')] public readonly ?string $profileImage,
        #[SerializedName('display_name')] public readonly ?string $displayName,
        public readonly ?string $link
    ) {}
}