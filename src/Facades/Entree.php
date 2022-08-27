<?php

namespace Lokalkoder\Entree\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Lokalkoder\Entree\Entree
 */
class Entree extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Lokalkoder\Entree\Entree::class;
    }
}
