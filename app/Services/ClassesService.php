<?php

namespace App\Services;


use App\Models\Book\Book;
use App\Models\Book\BookPlan;
use App\Models\Classes\Classes;
use App\Models\Classes\ClassesBookReceive;
use App\Models\Classes\ClassesReceive;
use App\Models\Student\Student;

class ClassesService extends ServiceBasic
{
    protected $model = Classes::class;

    public static function limit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
        $query = self::_getQuery($conditions, $deleted, $status);
        $list = $query
            ->leftJoin('class_receive',function($query){
                $query->on('class_receive.class_id','=','classes.id');
            })
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->get(['classes.*','class_receive.student_received as receivers'])
            ->toArray();
        array_walk($list, function(&$v) use ($conditions){
            if(!empty($v['receivers'])) {
                $v['receivers'] = array_values(json_decode($v['receivers'], true));
            }else{
                $v['receivers'] = [];
            }
            $v['year'] = $conditions['year'].'_'.$conditions['up_down'];
        });
        $classIds = array_column($list, 'id');

//        Db::raw('SELECT * FROM `article`WHERE JSON_CONTAINS(classes, \'["Mysql"]\');')
//        BookPlan::query()->whereIn()

        return $list;
        //return parent::limit($conditions, $limit, $page, $deleted, $status);
    }

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

    public static function receivedBookLimit(array $conditions, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(ClassesBookReceive::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $num = $query->count();
        if($num > 0) {
            $list = $query
                ->join('book', function ($query) {
                    $query->on('book.id', '=', 'class_book_receive.book_id');
                })
                ->get(['book.id as bid','book.name','book.sn','book.cost','class_book_receive.received_time','class_book_receive.id']);
            if(!empty($list)) {
                return $list->toArray();
            }
            return [];
        }else{
            return [];
        }
    }

    public function getOneBySnAndCheckReceive(string $sn, $userId) : array
    {
        self::setSelfModel(Book::class);
        $model = self::getModelInstance();

        $field = ['book.id','book.name','book.sn','book.cost','book_plan.plan_year','book_plan.up_down','book_plan.notebook_num','book_plan.id as plan_id'];
        $data = $model->newQuery()
            ->join('book_plan',function($query){
                $query->on('book_plan.book_id','=','book.id');
            })
            ->where('sn','=', $sn)->limit(1)->get($field);
        $data = empty($data) ? [] : $data->toArray();
        if(empty($data)) {
            return $data;
        }

        $data = $data[0];
        $classReceive = ClassesBookReceive::query()
            ->where('student_id','=',$userId)
            ->where('book_id','=',$data['id'])
            ->where('plan_id','=',$data['plan_id'])
            ->first(['id']);

        $data['received'] = true;
        if(empty($classReceive)) {
            $data['received'] = false;
        }

        return $data;
    }


    public static function receivedLimit(array $conditions, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(ClassesReceive::class);
        $query = self::_getQuery($conditions, $deleted, $status);
//        var_dump($query->toSql());
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
                    ->update(['student_no_receive'=>json_encode(array_values($noReceive))]);
            }

            return $response;
        }
        return [];
    }
}