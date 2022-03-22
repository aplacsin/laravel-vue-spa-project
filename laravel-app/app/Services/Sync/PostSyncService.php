<?php

namespace App\Services\Sync;

use App\Repositories\PostRepositoryInterface;
use App\Services\PostService;

class PostSyncService
{
    private PostRepositoryInterface $postRepository;
    private PostService $postService;

    public function __construct(PostRepositoryInterface $postRepository, PostService $postService)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
    }

    public function sync(string $url): void
    {
        $context = stream_context_create(array(
            'http' => array(
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            )
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
