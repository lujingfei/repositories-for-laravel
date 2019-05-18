<?php
/**
 * Created by PhpStorm.
 * User: lujingfei
 * Date: 2019/5/17
 * Time: 2:35 PM
 */

namespace Geoff\Repositories\Decorator;

use Geoff\Repositories\Contracts\RepositoryInterface as Repository;

abstract class Decorator
{
    /**
     * @param $model
     * @param Repository $repository
     * @return mixed
     */
    public abstract function apply($model, Repository $repository);
}