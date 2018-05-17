<?php
/**
 * Created by PhpStorm.
 * User: limars
 * Date: 2018/4/12
 * Time: 22:43
 */

namespace App\Services;


use App\Models\Classes\Classes;
use App\Models\Classes\ClassesReceive;
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

    public static function receivedLimit(array $conditions, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(ClassesReceive::class);
        $query = self::_getQuery($conditions, $deleted, $status);
//
//        $query->leftJoin('class_receive',function($query) {
//            $query->on('class_receive.student_id','=','student.id');
//        });

        $list = $query->first([
            'year','up_down','id',
                'student_no_receive','student_received'
            ]);

        if(!empty($list)) {
            $list = $list->toArray();
            $response = [];
            $received = json_decode($list['student_received'], true);
            $received = is_array($received) ? $received : [];
            $noReceive = json_decode($list['student_no_receive'], true);
            $receivedUser = [];
            $noReceivedUser = [];
            if(!empty($received)) {
                $receivedUser = Student::query()->whereIn('id', $received)->take(count($received))->get(['id','name'])->toArray();
            }

            $all = Student::query()->get(['id','name'])->toArray();

            if(is_array($all)) {
                foreach ($all as $value) {
                    if(!in_array($value['id'], $received)) {
                        $noReceivedUser[] = [
                            'id'    =>  $value['id'],
                            'name'  =>  $value['name'],
                            'receive'=> false
                        ];
                    }
                }
                $response = $noReceivedUser;
            }
            //var_dump($response);
            if(is_array($received)) {

                foreach ($receivedUser as $value) {
                    $response[] = [
                        'id'    =>  $value['id'],
                        'name'  =>  $value['name'],
                        'receive'=> true
                    ];
                }
            }

            if(count($noReceive) != $noReceivedUser) {
                $noReceive = array_column($noReceivedUser, 'id');
                ClassesReceive::query()->where('id','=',$list['id'])
                    ->update(['student_no_receive'=>json_encode($noReceive)]);
            }

            return $response;
        }
        return [];
    }
}