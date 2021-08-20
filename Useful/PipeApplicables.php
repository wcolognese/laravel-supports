<?php

namespace WColognese\LaravelSupports\Useful;

trait PipeApplicables
{
    /**
     * @param  array  $applicables
     * @param  mixed  $passable
     * @return mixed
     */
    protected function pipeApplicables(array $applicables, $passable)
    {
        return app(\Illuminate\Pipeline\Pipeline::class)
                    ->send($passable)
                        ->through($applicables)
                            ->thenReturn();
    }
}