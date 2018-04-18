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

    public function createPlan(): int
    {
        self::setSelfModel(BookPlan::class);
        return parent::create();
    }

    public function createOrder() : int
    {
        self::setSelfModel(BookOrder::class);
        return parent::create();
    }

    public function updatePlan($id): int
    {
        self::setSelfModel(BookPlan::class);
        if(in_array('book_id', $this->getAttr())) {
            throw new LogicException("不能修改计划的书本");
        }
        return parent::update($id);
    }

    public function updateOrder($id): int
    {
        self::setSelfModel(BookOrder::class);
        if(in_array('plan_id', $this->getAttr())) {
            throw new LogicException("不能修改已有的计划");
        }
        return parent::update($id);
    }

    public function deletePlan($id) : int
    {
        self::setSelfModel(BookPlan::class);
        return parent::delete($id);
    }

    public function deleteOrder($id) : int
    {
        self::setSelfModel(BookOrder::class);
        return parent::delete($id);
    }
}