<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Services\Exports\PostExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PostExportController extends Controller
{
    private PostExportService $postExportService;

    public function __construct(PostExportService $exportCSVService)
    {
        $this->postExportService = $exportCSVService;
    }

    /**
     * @throws Throwable
     */
    public function postExport(Request $request): StreamedResponse
    {
        return $this->postExportService->query($request);
    }
}
