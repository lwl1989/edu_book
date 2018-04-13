<?php
/**
 * Created by PhpStorm.
 * User: li
 * Date: 4/13/18
 * Time: 8:13 PM
 */

namespace App\Services\Users;


use App\Models\Student\Student;
use App\Services\ServiceBasic;

class StudentService extends ServiceBasic
{
    protected $model = Student::class;
}