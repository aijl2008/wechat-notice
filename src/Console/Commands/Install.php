<?php
namespace Awz\Notice\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Install extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notice:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install and setup wechat-note';


    /**
     * Install constructor.
     */
    public function __construct () {
        parent::__construct ();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle () {
        try {
            $this->line ( "php artisan queue:table" );
            Artisan::call ( 'queue:table' );
        } catch ( Exception $e ) {
        }
        try {
            $this->line ( "php artisan queue:failed-table" );
            Artisan::call ( 'queue:failed-table' );
        } catch ( Exception $e ) {
        }
        try {
            $this->line ( 'php artisan migrate' );
            Artisan::call ( 'migrate' );
            $this->line ( 'Installed successfully.You should start queue deamon, for example:' . PHP_EOL . 'php artisan queue:work --tries=3' );
        } catch ( Exception $e ) {
            $this->line ( PHP_EOL . '<error>An unexpected error occurred. Installation could not continue.</error>' );
            $this->error ( "[✘] {$e->getMessage()}" );
        }
    }
}
