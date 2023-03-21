<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Repositories\ProcessPostRepositoryInterface;
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
    public int $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data, array $header, int $id)
    {
        $this->data = $data;
        $this->header = $header;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        PostImportService              $postImportService,
        ProcessPostRepositoryInterface $processPostRepository
    )
    {
        $processPost = $processPostRepository->findById($this->id);

        foreach ($this->data as $post) {
            $processPost->current = count($this->data);
            $processPost->save();

            $postData = array_combine($this->header, $post);
            $postImportService->syncPost($postData);
        }
    }
}
