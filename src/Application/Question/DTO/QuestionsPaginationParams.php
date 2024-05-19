<?php

namespace App\Application\Question\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionsPaginationParams
{
    public function __construct(
        #[Assert\Range(minMessage: 'page must be greater than 0', min: 1)]
        public ?int $page,
        #[Assert\Range(maxMessage: 'length must be fewer than 100', max: 100)]
        public ?int $pagesize,
        #[Assert\Choice(choices: ["asc", "desc", null], message: "Invalid order value")]
        public ?string $order,
        #[Assert\Choice(choices: ["activity", "votes", "creation", null], message: "Invalid sort value")]
        public ?string $sort
    ) {
        $this->page = $page ?? 1;
        $this->pagesize = $pagesize ?? 10;
        $this->order = $order ?? "desc";
        $this->sort = $sort ?? "activity";
    }
}
