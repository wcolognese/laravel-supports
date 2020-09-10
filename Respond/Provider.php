<?php

namespace WColognese\LaravelSupports\Respond;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('respond', Respond::class);
    }
}