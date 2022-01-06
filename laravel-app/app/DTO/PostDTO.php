<?php

declare(strict_types=1);

namespace App\DTO;

class PostDTO
{
    private ?int $user_id;
    private string $title;
    private string $description;

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public static function make(string $title, string $description): self
    {
        $dto = new self();

        $dto->setTitle($title);
        $dto->setDescription($description);

        return $dto;
    }
}
