<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportFileRequest;
use App\Http\Resources\Post\PostProcessResource;
use App\Services\Imports\PostImportService;

class PostImportController extends Controller
{
    private PostImportService $postImportService;

    public function __construct(PostImportService $postImportService)
    {
        $this->postImportService = $postImportService;
    }

    public function import(ImportFileRequest $request): int
    {
        $id = $this->postImportService->processPost();
        $this->postImportService->importFile($request->file('importFile'), $id->id);

        return $id->id;
    }

    public function status(int $id): PostProcessResource
    {
        $status = $this->postImportService->status($id);

        return new PostProcessResource($status);
    }

    public function completed(int $id): void
    {
        $this->postImportService->complete($id);
    }
}
