<?php

namespace App\Console\Commands;

use App\Services\Sync\PostSyncService;
use App\Services\Sync\VideoSyncService;
use Illuminate\Console\Command;
use Vimeo\Laravel\VimeoManager;

class ParseData extends Command
{
    private PostSyncService $postSyncService;
    private VideoSyncService $videoSyncService;
    protected VimeoManager $vimeo;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse data hourly';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(PostSyncService $postSyncService, VideoSyncService $videoSyncService, VimeoManager $vimeo)
    {
        parent::__construct();
        $this->postSyncService = $postSyncService;
        $this->videoSyncService = $videoSyncService;
        $this->vimeo = $vimeo;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $url = config('parser.url');
        $videos = $this->vimeo->request(config('vimeo.url'), ['per_page' => 50]);

        $this->videoSyncService->sync($videos);
        $this->postSyncService->sync($url);

        echo "Success\n";
        return 0;
    }
}
