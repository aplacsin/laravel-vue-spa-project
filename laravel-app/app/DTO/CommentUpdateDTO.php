<?php

declare(strict_types=1);

namespace App\DTO;

class CommentUpdateDTO
{
    private string $content;

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public static function make(string $content): self
    {
        $dto = new self();

        $dto->setContent($content);

        return $dto;
    }
}
