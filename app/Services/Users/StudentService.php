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
    protected $listField = ['student.id','student.name','student.student_num','student.created_at','classes.name as class_name'];

    public static function limit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
            $query = self::_getQuery($conditions, $deleted, $status);

            $list = $query->skip(($page-1)*$limit)
                    ->join('classes',function ($query){
                            $query->on('classes.id','=','student.class_id');
                    })
                    ->take($limit)
                    ->get(self::getSelfListField())
                    ->toArray();

            return $list;
    }
}