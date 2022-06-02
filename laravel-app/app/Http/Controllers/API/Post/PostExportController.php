<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\Controller;
use App\Services\Exports\PostExportService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PostExportController extends Controller
{
    private PostExportService $postExportService;

    public function __construct(PostExportService $postExportService)
    {
        $this->postExportService = $postExportService;
    }

    public function postExport(Request $request): StreamedResponse
    {
        return $this->postExportService->export($request->input('id'));
    }
}
