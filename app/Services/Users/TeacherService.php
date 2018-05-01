<?php

namespace App\Services\Users;


use App\Models\Book\Book;
use App\Models\Teacher\Teacher;
use App\Models\Teacher\TeacherReceive;
use App\Services\ServiceBasic;
use Illuminate\Support\Facades\DB;

class TeacherService extends ServiceBasic
{
    protected $model = Teacher::class;
    protected $listField = ['id','name','created_at','teacher_num','mobile'];

    public static function receiveLimit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(TeacherReceive::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $num = $query->count();

        if($num > 0) {
            $limit = $limit === 0 ? $num : $limit;
            $list = $query->skip(($page - 1) * $limit)
                ->join('book', function ($query) {
                    $query->on('book.id', '=', 'teacher_receive.book_id');
                })
                ->take($limit)
                ->get(['book.id as bid','book.name','book.sn','book.cost','teacher_receive.received_time','teacher_receive.id']);
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
            ->where('sn','=', $sn)
            ->limit(1)
            ->get($field);

        $data = empty($data) ? [] : $data->toArray();
        if(empty($data)) {
            return $data;
        }

        $data = $data[0];
        $teacherReceive = TeacherReceive::query()
            ->where('teacher_id','=',$userId)
            ->where('book_id','=',$data['id'])
            ->where('plan_id','=',$data['plan_id'])
            ->first(['id']);

        $data['received'] = true;
        if(empty($teacherReceive)) {
            $data['received'] = false;
        }

        return $data;
    }

    public static function doReceive($id, array $books) : bool
    {
        try {
            DB::transaction(function () use ($id, $books) {
                $time = date("Y-m-d H:i:s");
                foreach ($books as $book) {
                    TeacherReceive::insert([
                        'teacher_id' => $id,
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