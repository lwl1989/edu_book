<?php
/**
 * Created by PhpStorm.
 * User: li
 * Date: 4/13/18
 * Time: 8:13 PM
 */

namespace App\Services\Users;


use App\Models\Teacher\Teacher;
use App\Services\ServiceBasic;

class TeacherService extends ServiceBasic
{
    use UserReceiveTrait;
    protected $model = Teacher::class;
}