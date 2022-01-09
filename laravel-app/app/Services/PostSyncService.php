<?php

namespace App\Services;

use App\Repositories\PostRepositoryInterface;

class PostSyncService
{
    private PostRepositoryInterface $postRepository;
    private PostService $postService;

    public function __construct(PostRepositoryInterface $postRepository, PostService $postService)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
    }

    public function sync($url): void
    {
        $context = stream_context_create(array(
            'http' => array('timeout' => 6)
        ));
        $file = file_get_contents($url, 0, $context);

        if (!$file) {
            return;
        }

        $rss = simplexml_load_string($file);

        foreach ($rss->channel->item as $post) {
            $this->syncPost($post);
        }
    }

    private function syncPost($post): void
    {
        $modelPost = $this->postRepository->findByGuId($post->guid);

        if ($modelPost == null) {
            $this->postService->create($post);
        }
    }
}
