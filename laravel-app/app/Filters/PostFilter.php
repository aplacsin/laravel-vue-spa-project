<?php

namespace App\Filters;

class PostFilter
{
    private int $page;
    private ?string $search;

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return string|null
     */
    public function getSearch(): ?string
    {
        return $this->search;
    }

    /**
     * @param string|null $search
     */
    public function setSearch(?string $search): void
    {
        $this->search = $search;
    }

    public static function make(int $page, ?string $search = null): self
    {
        $dto = new self();

        $dto->setPage($page);
        $dto->setSearch($search);

        return $dto;
    }
}
