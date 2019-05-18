<?php
/**
 * Created by PhpStorm.
 * User: lujingfei
 * Date: 2019/5/17
 * Time: 2:24 PM
 */

namespace Geoff\Repositories\Contracts;

use Geoff\Repositories\Decorator\Decorator;

interface DecoratorInterface
{
    /**
     * @param bool $status
     * @return $this
     */
    public function skipDecorator($status = true);

    /**
     * @return mixed
     */
    public function getDecorators();

    /**
     * @param Decorator $decorator
     * @return $this
     */
    public function getByDecorator(Decorator $decorator);

    /**
     * @param Decorator $decorator
     * @return $this
     */
    public function pushDecorator(Decorator $decorator);

    /**
     * @return $this
     */
    public function applyDecorator();
}