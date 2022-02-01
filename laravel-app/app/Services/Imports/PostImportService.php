<?php

namespace App\Services\Imports;

use App\Jobs\PostImportJob;
use App\Models\Post;
use App\Repositories\PostRepositoryInterface;
use Throwable;

class PostImportService
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @throws Throwable
     */
    public function fileImport($file): void
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
}
