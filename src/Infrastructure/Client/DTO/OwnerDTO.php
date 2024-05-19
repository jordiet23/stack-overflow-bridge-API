<?php

namespace App\Infrastructure\Client\DTO;

use Symfony\Component\Serializer\Attribute\SerializedName;

class OwnerDTO
{
    public function __construct(
        #[SerializedName('account_id')] public int $accountId,
        public int $reputation,
        #[SerializedName('user_id')] public int $userId,
        #[SerializedName('user_type')] public string $userType,
        #[SerializedName('profile_image')] public string $profileImage,
        #[SerializedName('display_name')] public string $displayName,
        public string $link
    ) {}
}