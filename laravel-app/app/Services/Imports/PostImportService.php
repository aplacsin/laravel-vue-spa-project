<?php

namespace App\Services\Imports;

use App\Jobs\PostImportJob;
use App\Models\Post;
use App\Models\ProcessPost;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\ProcessPostRepositoryInterface;
use Throwable;

class PostImportService
{
    private PostRepositoryInterface $postRepository;
    private ProcessPostRepositoryInterface $processPostRepository;

    public function __construct(PostRepositoryInterface $postRepository, ProcessPostRepositoryInterface $processPostRepository)
    {
        $this->postRepository = $postRepository;
        $this->processPostRepository = $processPostRepository;
    }

    /**
     * @throws Throwable
     */
    public function importFile($file): void
    {
        if(!$file) {
            return;
        }

        $file->storeAs('public/imports', 'imports.csv');
        $this->parseFile();
    }

    /**
     * @throws Throwable
     */
    public function parseFile()
    {
        $header = [];
        $data = [];

        if(($handle = fopen(storage_path('app/public/imports/imports.csv'), 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
            unlink(storage_path('app/public/imports/imports.csv'));
        }

        $this->chunkData($data, $header);
    }

    /**
     * @throws Throwable
     */
    public function chunkData($data, $header)
    {
        $countPost = count($data);
        $processPost = new ProcessPost();
        $processPost->total = $countPost;
        $processPost->save();

        $chunks = array_chunk($data, 2000);

        foreach ($chunks as $chunk) {
            $data = array_map('array_values', $chunk);
            PostImportJob::dispatch($data, $header);
        }
    }

    public function syncPost($post): void
    {
        $modelPost = $this->postRepository->findByGuId($post['guid']);

        if ($modelPost == null) {
            $this->import($post);
        }
    }

    public function import($item): bool
    {
        $post = new Post();
        $post->title = $item['title'];
        $post->description = $item['description'];
        $post->guid = $item['guid'];

        return $this->postRepository->save($post);
    }

    public function status(int $id)
    {
        return $this->processPostRepository->findById($id);
    }

    public function complete(bool $status): bool
    {
        $complete = $this->processPostRepository->findById(1);
        $complete->processed = $status;

        return $this->processPostRepository->save($complete);
    }
}