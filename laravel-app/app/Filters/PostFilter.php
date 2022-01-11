<?php

namespace App\Filters;

class PostFilter
{
    private int $page;
    private ?string $search;
    private ?string $startDate;
    private ?string $endDate;

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


    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @param string|null $startDate
     */
    public function setStartDate(?string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string|null
     */
    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    /**
     * @param string|null $endDate
     */
    public function setEndDate(?string $endDate): void
    {
        $this->endDate = $endDate;
    }

    public static function make(int $page, ?string $search = null, ?string $startDate = null, ?string $endDate = null): self
    {
        $dto = new self();

        $dto->setPage($page);
        $dto->setSearch($search);
        $dto->setStartDate($startDate);
        $dto->setEndDate($endDate);

        return $dto;
    }
}
