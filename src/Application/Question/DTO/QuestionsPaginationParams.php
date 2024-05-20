<?php

namespace App\Application\Question\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionsPaginationParams
{
    public function __construct(
        #[Assert\Range(minMessage: 'page must be greater than 0', min: 1)]
        private ?int $page,
        #[Assert\Range(maxMessage: 'length must be fewer than 100', max: 100)]
        private ?int $pagesize,
        #[Assert\Choice(choices: ["asc", "desc", null], message: "Invalid order value")]
        private ?string $order,
        #[Assert\Choice(choices: ["activity", "votes", "creation", "hot", "week", "month", null], message: "Invalid sort value")]
        private ?string $sort
    ) {
        $this->page = $page ?? 1;
        $this->pagesize = $pagesize ?? 10;
        $this->order = $order ?? "desc";
        $this->sort = $sort ?? "activity";
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'pagesize' => $this->pagesize,
            'order' => $this->order,
            'sort' => $this->sort,
        ];
    }

    public function getPage(): ?int
    {
        return $this->page;
    }

    public function getPagesize(): ?int
    {
        return $this->pagesize;
    }

    public function getOrder(): ?string
    {
        return $this->order;
    }

    public function getSort(): ?string
    {
        return $this->sort;
    }
}
