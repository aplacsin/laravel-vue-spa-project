<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportFileRequest;
use App\Services\Imports\PostImportService;
use Throwable;

class PostImportController extends Controller
{
    private PostImportService $postImportService;

    public function __construct(PostImportService $postImportService)
    {
        $this->postImportService = $postImportService;
    }

    /**
     * @throws Throwable
     */
    public function import(ImportFileRequest $request)
    {
        $this->postImportService->fileImport($request->file('importFile'));
    }
}
