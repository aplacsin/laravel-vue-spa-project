<?php

namespace App\Services\Exports;

use App\Jobs\PostExportJob;
use App\Models\Post;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PostExportService
{
    /**
     * @throws Throwable
     */
    public function query($request): StreamedResponse
    {
        $postsArray = explode(',', $request->input('id'));
        $posts = Post::query()->whereKey($postsArray)->get();

        return $this->map($posts);
    }

    /**
     * @throws Throwable
     */
    public function map($posts): StreamedResponse
    {
        $header = array('guid', 'title', 'description');

        $callback = function() use($posts, $header) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $header);

            foreach ($posts as $post) {
                $row['guid']  = $post->guid;
                $row['title']    = $post->title;
                $row['description']  = $post->description;

                fputcsv($file, array($row['guid'], $row['title'], $row['description']));
            }

            /*PostExportJob::dispatch($posts, $file);*/

            fclose($file);
        };

        return $this->export($callback);
    }

    public function export($callback): StreamedResponse
    {
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=export.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        return response()->stream($callback, 200, $headers);
    }
}
