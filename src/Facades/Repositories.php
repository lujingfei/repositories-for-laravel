<?php
/**
 * Created by PhpStorm.
 * User: lujingfei
 * Date: 2019/5/17
 * Time: 2:30 PM
 */

namespace Geoff\Repositories\Facades;

use Illuminate\Support\Facades\Facade;

class Repositories extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'repositories';
    }
}