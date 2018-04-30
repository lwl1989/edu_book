<?php
/**
 * Created by PhpStorm.
 * User: limars
 * Date: 2018/4/12
 * Time: 22:43
 */

namespace App\Services;


use App\Models\Classes\Classes;
use App\Models\Student\Student;

class ClassesService extends ServiceBasic
{
    protected $model = Classes::class;

    public static function payLimit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(Student::class);
        $query = self::_getQuery($conditions, $deleted, $status);

        $query->leftJoin('student_payment',function($query) {
            $query->on('student_payment.student_id','=','student.id');
        });

        $list = $query->skip(($page-1)*$limit)
            ->limit($limit)
            ->get([
                'student.id','student.name','student_payment.payed','student_payment.pay_time'
            ]);

        return empty($list) ? [] : $list->toArray();
    }
}