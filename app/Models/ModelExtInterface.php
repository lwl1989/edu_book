<?php

namespace App\Models;


interface ModelExtInterface
{

    public function enableFormat();

    public function disableFormat();

    public function getEnableFormat() : bool;

    public function getFormat() : array;

}