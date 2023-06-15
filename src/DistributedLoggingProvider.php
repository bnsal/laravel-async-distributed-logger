<?php

namespace Bnsal\DistributedLogger;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;


class DistributedLoggingProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        
        $this->mergeConfigFrom(
            __DIR__.'/bnsallogging.php', 'bnsallogging'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Contracts\Http\Kernel $kernel) {
        $this->publishes([
            __DIR__.'/bnsallogging.php' => config_path('bnsallogging.php'),
        ]);

        if( config('bnsallogging.disable_default_logging') ) {
            config( [ 'logging.default' => 'null' ] );
        }

        $this->app->singleton(DistributedLoggingController::class, function ($app) {
            return new DistributedLoggingController();
        });

        $logger = app('log');
        $logger->pushHandler(new DistributedLoggingHandler( config('bnsallogging.log_level', Logger::DEBUG) ));

        $router = $this->app['router'];         
        $router->pushMiddlewareToGroup('web', DistributedLoggingMiddleware::class);

        if( config('bnsallogging.eventsToLog') ) {
            $eventsToLog = config('bnsallogging.eventsToLog');
            foreach ($eventsToLog as $key => $event) {
                \Illuminate\Support\Facades\Event::listen(
                    $event,
                    LoggingListener::class
                );
            }
        }

    }
}
