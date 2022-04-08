<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\ProcessPost;
use App\Services\Imports\PostImportService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostImportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public array $header;
    public array $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, array $header)
    {
        $this->data = $data;
        $this->header = $header;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PostImportService $postImportService)
    {
        $processPost = new ProcessPost();

        foreach ($this->data as $post) {
            /*$processPost->current = count($post);
            $processPost->save();*/

            $postData = array_combine($this->header, $post);
            $postImportService->syncPost($postData);
        }
    }
}
