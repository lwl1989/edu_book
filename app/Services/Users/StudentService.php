<?php

namespace App\Services\Users;


use App\Models\Book\Book;
use App\Models\Classes\ClassesReceive;
use App\Models\Student\Student;
use App\Models\Student\StudentPay;
use App\Models\Student\StudentReceive;
use App\Services\ServiceBasic;
use Illuminate\Support\Facades\DB;

class StudentService extends ServiceBasic
{
    protected $model = Student::class;
    protected $listField = ['student.id','student.name','student.student_num','student.created_at','classes.name as class_name'];

    /**
     * 批量对某班级的学生执行出库
     * @param $classId
     * @param array $students
     * @param $year
     * @param int $upDown
     * @return bool
     */
    public static function batchReceive($classId, array $students, $year, $upDown = 0) : bool
    {

        try {

            $exists = ClassesReceive::query()->where('class_id','=',$classId)
                ->where('year','=', $year)
             //   ->where('un_down', '=', $upDown)
                ->first(['id','student_received']);

            if(!empty($exists)) {
                $exists = $exists->toArray();

                $exists['student_received'] = json_decode($exists['student_received']);
                $exists['student_received'] = is_array($exists['student_received']) ? $exists['student_received'] : [];

                //$exists['student_received'] = array_merge($exists['student_received']);

                $students = array_map(function ($v){
                    return intval($v);
                }, array_unique(array_merge($exists['student_received'], $students)));
                ClassesReceive::query()->where('id','=',$exists['id'])
                    ->update(['student_received'=>json_encode(array_values($students))]);
            }else{
                ClassesReceive::insert([
                    'student_received'=>json_encode(array_values($students)),
                    'class_id' =>$classId,
                    'year'=>$year,
                    'un_down'=>$upDown
                ]);
            }


            return true;
        }catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * 获取学生单独领取书籍的列表
     * @param array $conditions
     * @param int $limit
     * @param int $page
     * @param bool $deleted
     * @param int $status
     * @return array
     */
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

    /**
     * 获取学生单独领取书籍的列表
     * @param array $conditions
     * @param int $limit
     * @param int $page
     * @param bool $deleted
     * @param int $status
     * @return array
     */
    public static function receiveLimit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(StudentReceive::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $num = $query->count();
        if($num > 0) {
            $limit = $limit === 0 ? $num : $limit;
            $list = $query->skip(($page - 1) * $limit)
                ->join('book', function ($query) {
                    $query->on('book.id', '=', 'student_receive.book_id');
                })
                ->take($limit)
                ->get(['book.id as bid','book.name','book.sn','book.cost','student_receive.received_time','student_receive.id']);
            if(!empty($list)) {
                return $list->toArray();
            }
            return [];
        }else{
            return [];
        }
    }

    /**
     * 获取某本书是否被某个学生领取过
     * @param string $sn
     * @param $userId
     * @return array
     */
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
        $studentReceive = StudentReceive::query()
            ->where('student_id','=',$userId)
            ->where('book_id','=',$data['id'])
            ->where('plan_id','=',$data['plan_id'])
            ->first(['id']);

        $data['received'] = true;
        if(empty($studentReceive)) {
            $data['received'] = false;
        }

        return $data;
    }

    /**
     * 执行学生单独领书出库
     * @param $id
     * @param array $books
     * @return bool
     */
    public static function doReceive($id, array $books) : bool
    {
        try {
            DB::transaction(function () use ($id, $books) {
                $time = date("Y-m-d H:i:s");
                foreach ($books as $book) {
                    StudentReceive::insert([
                        'student_id' => $id,
                        'book_id' => $book['id'],
                        'notebook_num' =>  $book['notebook_num'],
                        'received'  =>  1,
                        'received_time' =>  $time,
                        'plan_id'   =>  $book['plan_id']
                    ]);
                    Book::outStock($book['id'],1);
                }
            }, 1);
            return true;
        }catch (\Exception $exception) {
            return false;
        }
    }
}