<?php

namespace Inboxly\Receiver;

use FeedIo\Adapter\Guzzle\Client as ClientAdapter;
use FeedIo\FeedIo;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;
use Psr\Log\NullLogger;

class FeedIoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClientInterface::class, Client::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $guzzle = $this->app->make(ClientInterface::class);
        $feedIo = new FeedIo(new ClientAdapter($guzzle), new NullLogger());

        $this->app->instance(FeedIo::class, $feedIo);
    }
}
