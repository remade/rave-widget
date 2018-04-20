<?php

namespace Remade\RaveWidget\Facades;

use Illuminate\Support\Facades\Facade;

class RaveWidget extends Facade
{
    /**
     * Get the registered name of the component
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'rave.widget';
    }
}
