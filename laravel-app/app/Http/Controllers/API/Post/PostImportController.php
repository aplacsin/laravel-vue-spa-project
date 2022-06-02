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

    public function import(ImportFileRequest $request)
    {
        $this->postImportService->importFile($request->file('importFile'));
    }

    public function status(int $id): PostProcessResource
    {
        $status = $this->postImportService->status($id);

        return new PostProcessResource($status);
    }

    public function completed(bool $status)
    {
        $this->postImportService->complete($status);
    }
}
