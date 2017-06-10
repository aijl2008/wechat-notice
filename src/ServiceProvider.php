<?php
namespace Awz\Notice;

use Awz\Notice\Models\Setting;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot () {
        require realpath ( __DIR__ . '/../routes/web.php' );
        if ( $this->app->runningInConsole () ) {
            $this->commands ( [ Console\Commands\Install::class ] );
        }
        $this->publishes ( [
            __DIR__ . '/../config/notice.php' => config_path ( 'notice.php' ) ,
        ] , 'config' );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register () {
        //
    }
}
