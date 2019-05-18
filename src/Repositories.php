<?php
/**
 * Created by PhpStorm.
 * User: lujingfei
 * Date: 2019/5/17
 * Time: 2:30 PM
 */

namespace Geoff\Repositories;

use Illuminate\Session\SessionManager;
use Illuminate\Config\Repository;

class Repositories
{

    /*
     * @var SessionManager
     */
    protected $session;

    /*
     * @var ConfigRespository
     */
    protected $config;

    public function __construct(SessionManager $session, Repository $config)
    {
        $this->session = $session;
        $this->config = $config;
    }
}