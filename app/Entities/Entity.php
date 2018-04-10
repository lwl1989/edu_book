<?php


namespace App\Entities;


class Entity
{
    use EntityTrait;

    public function __construct()
    {
        self::$instance = $this;
    }
}