<?php
namespace Awz\Notice\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotice implements ShouldQueue {
    use InteractsWithQueue , Queueable , SerializesModels;

    protected $app;
    protected $notice;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct ( $app , $notice ) {
        $this->app = $app;
        $this->notice = $notice;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle () {
        $this->app->send ( $this->notice );
        sleep ( 10 );
    }
}
