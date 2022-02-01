<?php

namespace App\Services\Filters;

class PostFilter
{
    private int $page;
    private ?string $search;
    private ?string $startDate;
    private ?string $endDate;
    private ?string $sortField;
    private ?string $sortDirection;

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

    /**
     * @return string|null
     */
    public function getSortField(): ?string
    {
        return $this->sortField;
    }

    /**
     * @param string|null $sortField
     */
    public function setSortField(?string $sortField): void
    {
        $this->sortField = $sortField;
    }

    /**
     * @return string|null
     */
    public function getSortDirection(): ?string
    {
        return $this->sortDirection;
    }

    /**
     * @param string|null $sortDirection
     */
    public function setSortDirection(?string $sortDirection): void
    {
        $this->sortDirection = $sortDirection;
    }

    public static function make(int $page, ?string $search = null, ?string $startDate = null, ?string $endDate = null, ?string $sortField = null, ?string $sortDirection = null): self
    {
        $dto = new self();

        $dto->setPage($page);
        $dto->setSearch($search);
        $dto->setStartDate($startDate);
        $dto->setEndDate($endDate);
        $dto->setSortField($sortField);
        $dto->setSortDirection($sortDirection);

        return $dto;
    }
}
