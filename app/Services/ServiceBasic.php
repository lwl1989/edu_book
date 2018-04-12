<?php


namespace App\Services;



class ServiceBasic
{
    use ServiceTrait;
    protected $serviceName = '';
    public function __construct()
    {
        self::$instance = $this;
    }

}