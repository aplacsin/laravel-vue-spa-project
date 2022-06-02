<?php

declare(strict_types=1);

namespace App\Services\Exports;

use App\Enums\DiskType;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostExportService
{
    const LIMIT = 1000;
    const FILE_NAME = 'export.csv';

    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function export(?string $id): StreamedResponse
    {
        $offset = 0;
        $limit = self::LIMIT;
        $ids = null;

        Storage::disk(DiskType::public()->value)->put(self::FILE_NAME, '');
        $file = Storage::disk(DiskType::public()->value)->path(self::FILE_NAME);

        $handle = fopen($file, 'w');
        fputcsv($handle, $this->headerList());

        if ($id) {
            $ids = explode(',', $id);
        }

        while (true) {
            $posts = $this->postRepository->listPosts($offset, $limit, $ids);

            if (count($posts) === 0) {
                fclose($handle);
                break;
            }

            foreach ($posts as $post) {
                $fields = $this->map(json_decode(json_encode($post), true));
                fputcsv($handle, $fields);
            }

            $offset += $limit;
        }

        return $this->download();
    }

    public function headerList(): array
    {
        return [
            'guid' => 'GUID',
            'title' => 'TITLE',
            'description' => 'DESCRIPTION'
        ];
    }

    public function map(array $post): array
    {
        return [
            'guid' => $post['guid'],
            'title' => $post['title'],
            'description' => $post['description']
        ];
    }

    public function download(): StreamedResponse
    {
        return Storage::disk(DiskType::public()->value)->download(self::FILE_NAME);
    }
}
