<?php

namespace App\Services;

use App\DTO\PostDTO;
use App\Filters\PostFilter;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function create($item): bool
    {
        $namespaces = $item->getNamespaces(true);
        $yandex = $item->children($namespaces['yandex']);

        $post = new Post();
        $post->title = $item->title;
        $post->description = html_entity_decode((string)$yandex->{'full-text'});
        $post->guid = $item->guid;

        return $this->postRepository->save($post);
    }

    public function update(PostDTO $postDTO, int $id): void
    {
        $post = $this->postRepository->findById($id);
        $post->title = $postDTO->getTitle();
        $post->description = $postDTO->getDescription();

        $this->postRepository->save($post);
    }

    public function getPosts(PostFilter $postFilter): LengthAwarePaginator
    {
        return $this->postRepository->list($postFilter);
    }

    public function getById(int $id)
    {
        return $this->postRepository->findById($id);
    }

    public function delete(int $id): void
    {
        $this->postRepository->removeById($id);
    }
}
