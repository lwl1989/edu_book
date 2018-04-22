<?php
/**
 * Created by PhpStorm.
 * User: limars
 * Date: 2018/4/12
 * Time: 22:43
 */

namespace App\Services;


use App\Exceptions\LogicException;
use App\Models\Book\Book;
use App\Models\Book\BookOrder;
use App\Models\Book\BookPlan;
use App\Models\Classes\Classes;
use Illuminate\Database\Query\JoinClause;

class BookService extends ServiceBasic
{
    protected $model = Book::class;


    /**
     * 计划列表
     * @param array $conditions
     * @param int $limit
     * @param int $page
     * @param bool $deleted
     * @param int $status
     * @return array
     */
    public static function planLimit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(BookPlan::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $list = $query
            ->join('book',function($query){
                $query->on('book.id', '=', 'book_plan.book_id');
            })
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->get(self::getSelfListField())
            ->toArray();

        if(!empty($list)) {
            $classesIds = [];
            foreach ($list as &$v) {
                if(!empty($v['classes']) and !is_array($v['classes'])) {
                    $v['classes'] = json_decode($v['classes']);
                }
                $classesIds += $v['classes'];
            }
            $classes = Classes::query()->whereIn('id', $classesIds)->limit(count($classesIds))->get(['id','name'])->toArray();
            $classes = array_column($classes, 'name','id');
            foreach ($list as &$v) {
                $classesList = [];
                if(!empty($v['classes'])) {
                    foreach ($v['classes'] as $id) {
                        $classesList[] = $classes[$id];
                    }
                }
                $v['class_list'] = implode($classesList,'、');
            }

        }
        return $list;
    }

    /**
     * 订单列表
     * @param array $conditions
     * @param int $limit
     * @param int $page
     * @param bool $deleted
     * @param int $status
     * @return array
     */
    public static function orderLimit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1): array
    {
        self::setSelfModel(BookOrder::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $field = self::getSelfListField();
        $field = array_merge($field, ['book.name','book.sn','book.cost'],
            ['book_plan.plan_year','book_plan.up_down','book.created_at as plan_created','book.updated_at as plan_updated']);


        $list = $query
            ->join('book_plan',function($query){
                /** @var JoinClause $query */
                $query->on('book_plan.id', '=', 'book_order.plan_id');
            })
            ->join('book',function ($query){
                /** @var JoinClause $query */
                $query->on('book.id', '=', 'book_plan.book_id');
            })
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->get($field)
            ->toArray();
        return $list;
    }

    /**
     * 創建書籍計劃
     * @return int
     */
    public function createPlan(): int
    {
        self::setSelfModel(BookPlan::class);
        return parent::create();
    }

    /**
     * 創建書籍訂單
     * @return int
     */
    public function createOrder() : int
    {
        self::setSelfModel(BookOrder::class);
        return parent::create();
    }

    /**
     * 更新书籍计划
     * @param $id
     * @return int
     */
    public function updatePlan($id): int
    {
        self::setSelfModel(BookPlan::class);
        if(in_array('book_id', $this->getAttr())) {
            throw new LogicException("不能修改计划的书本");
        }
        return parent::update($id);
    }

    /**
     * 更新订单
     * @param $id
     * @return int
     */
    public function updateOrder($id): int
    {
        self::setSelfModel(BookOrder::class);
        if(in_array('plan_id', $this->getAttr())) {
            throw new LogicException("不能修改已有的计划");
        }
        return parent::update($id);
    }

    /**
     * 删除计划
     * @param $id
     * @return int
     */
    public function deletePlan($id) : int
    {
        self::setSelfModel(BookPlan::class);
        return parent::delete($id);
    }

    /**
     * 删除订单
     * @param $id
     * @return int
     */
    public function deleteOrder($id) : int
    {
        self::setSelfModel(BookOrder::class);
        return parent::delete($id);
    }

    /**
     * @param array $conditions
     * @param bool $deleted
     * @param int $status
     * @return int
     */
    public static function countPlan(array $conditions, bool $deleted = false, int $status = -1) : int
    {
        self::setSelfModel(BookPlan::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $count = $query->count();
        return intval($count);
    }

    /**
     * @param array $conditions
     * @param bool $deleted
     * @param int $status
     * @return int
     */
    public static function countOrder(array $conditions, bool $deleted = false, int $status = -1) : int
    {
        self::setSelfModel(BookOrder::class);
        $query = self::_getQuery($conditions, $deleted, $status);
        $count = $query->count();
        return intval($count);
    }

    /**
     * @param $id
     * @return array
     */
    public static function getOnePlan($id) : array
    {
        self::setSelfModel(BookPlan::class);
        $model = self::getModelInstance();
        $data = $model->newQuery()->find($id);
        return empty($data) ? [] : $data->toArray();
    }

    /**
     * @param $id
     * @return array
     */
    public static function getOneOrder($id) : array
    {
        self::setSelfModel(BookPlan::class);
        $model = self::getModelInstance();
        $data = $model->newQuery()->find($id);
        return empty($data) ? [] : $data->toArray();
    }
}