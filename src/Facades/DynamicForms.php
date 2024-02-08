<?php

namespace JibayMcs\DynamicForms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \JibayMcs\DynamicForms\DynamicForms
 */
class DynamicForms extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \JibayMcs\DynamicForms\DynamicForms::class;
    }
}
