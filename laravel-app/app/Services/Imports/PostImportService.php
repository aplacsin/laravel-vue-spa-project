<?php

namespace App\Services\Imports;

use App\Enums\DiskType;
use App\Jobs\PostImportJob;
use App\Models\Post;
use App\Models\ProcessPost;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\ProcessPostRepositoryInterface;
use Illuminate\Support\Facades\Storage;
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
        if (!$file) {
            return;
        }
        Storage::disk(DiskType::public()->value)->putFileAs('imports', $file, 'imports.csv');

        $this->parseFile();
    }

    /**
     * @throws Throwable
     */
    public function parseFile()
    {
        $header = [];
        $data = [];

        if (($handle = fopen(storage_path('app/public/imports/imports.csv'), 'r')) !== FALSE) {
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
    public function chunkData(array $data, array $header)
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

    public function syncPost(array $post): void
    {
        $modelPost = $this->postRepository->findByGuId($post['guid']);

        if ($modelPost == null) {
            $this->import($post);
        }
    }

    public function import(array $item): bool
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
