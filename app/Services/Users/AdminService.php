<?php


namespace App\Services;


use App\Models\Admin;
use App\Services\Users\UserReceiveTrait;

class AdminService extends ServiceBasic
{
    use UserReceiveTrait;
    protected $model = Admin::class;
}