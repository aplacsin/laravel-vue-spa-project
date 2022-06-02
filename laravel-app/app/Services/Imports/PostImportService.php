<?php

declare(strict_types=1);

namespace App\Services\Imports;

use App\Enums\DiskType;
use App\Jobs\PostImportJob;
use App\Models\Post;
use App\Models\ProcessPost;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\ProcessPostRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class PostImportService
{
    private PostRepositoryInterface $postRepository;
    private ProcessPostRepositoryInterface $processPostRepository;

    public function __construct(
        PostRepositoryInterface        $postRepository,
        ProcessPostRepositoryInterface $processPostRepository
    )
    {
        $this->postRepository = $postRepository;
        $this->processPostRepository = $processPostRepository;
    }

    public function importFile(object $file): void
    {
        if (!$file) {
            return;
        }

        Storage::disk(DiskType::public()->value)->putFileAs('imports', $file, 'imports.csv');

        $this->parseFile($file);
    }

    public function parseFile(object $file): void
    {
        $header = [];
        $data = [];
        $path = storage_path('app/public/imports/imports.csv');
        $fileName = $file->getClientOriginalName();

        if (($handle = fopen($path, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
            unlink($path);
        }

        $this->chunkData($data, $header, $fileName);
    }

    public function chunkData(array $data, array $header, string $fileName): void
    {
        $totalPost = count($data);
        $processPost = new ProcessPost();
        $processPost->total = $totalPost;
        $processPost->file_name = $fileName;
        $processPost->save();
        $insertId = $processPost->id;

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

    public function import(array $item): void
    {
        $post = new Post();
        $post->title = $item['title'];
        $post->description = $item['description'];
        $post->guid = $item['guid'];

        $this->postRepository->save($post);
    }

    public function status(int $id)
    {
        return $this->processPostRepository->findById($id);
    }

    public function complete(bool $status): void
    {
        $complete = $this->processPostRepository->findById(1);
        $complete->processed = $status;

        $this->processPostRepository->save($complete);
    }
}
