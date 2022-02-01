<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PostExportJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $file)
    {
        $this->data = $data;
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->data as $post) {
            $row['guid']  = $post->guid;
            $row['title']    = $post->title;
            $row['description']  = $post->description;

            fputcsv($this->file, array($row['guid'], $row['title'], $row['description']));
        }
    }
}
