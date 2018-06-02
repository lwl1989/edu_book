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
use App\Models\Book\BookClassPlan;
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
        $keyword = "";
        if(isset($conditions['keyword'])) {
            $keyword = $conditions['keyword'];
            unset($conditions['keyword']);
        }

        $query = self::_getQuery($conditions, $deleted, $status);
        $list = $query
            ->join('book',function($query) use($keyword){
                $query->on('book.id', '=', 'book_plan.book_id');
                if(!empty($keyword)) {
                    $query->where('book.name','like', "%{$keyword}%");
                }
            })
            ->skip(($page-1)*$limit)
            ->take($limit)
            ->get(self::getSelfListField())
            ->toArray();

        if(!empty($list)) {
            $classPlan = BookClassPlan::query()
                ->whereIn('plan_id', array_column($list, 'id'))
                ->get(['plan_id','class_id'])->toArray();
            $classesIds = array_column($classPlan, 'class_id');
            $classes = Classes::query()->whereIn('id', $classesIds)->limit(count($classesIds))->get(['id','name'])->toArray();
            $classes = array_column($classes, 'name','id');
            foreach ($list as &$v) {
                $classesList = [];
                foreach ($classPlan as  $value) {
                    if($v['id'] == $value['plan_id']) {
                        $classesList[] = $classes[$value['class_id']];
                    }
                }
                $v['class_list'] = implode($classesList,'、');
                $v['name'] = sprintf('%s-%s-%s',$v['name'],$v['plan_year'],self::getUpDownString($v['up_down']));
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
        if(count($field) == 1) {
            $field[0] = 'book_order.*';
        }
        $field = array_merge($field, ['book.name','book.sn','book.cost','book.author','book.company'],
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

        $classes = [];
        if(isset($this->attr['classes'])) {
            $classes = $this->getAttr('classes');
            unset($this->attr['classes']);
        }
        $id = parent::create();

        if(!empty($classes)) {
            foreach ($classes as $cid) {
                BookClassPlan::query()->insert([
                   'plan_id'    =>  $id,
                   'class_id'   =>  $cid
                ]);
            }
        }

        return $id;
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
     * 更新教材计划
     * @param $id
     * @return int
     */
    public function updatePlan($id): int
    {
        self::setSelfModel(BookPlan::class);
        if(in_array('book_id', $this->getAttr())) {
            throw new LogicException("不能修改计划的书本");
        }
        $classes = [];
        if(isset($this->attr['classes'])) {
            $classes = $this->getAttr('classes');
            unset($this->attr['classes']);
        }
        $row = parent::update($id);

        if(!empty($classes)) {
            BookClassPlan::query()->where('plan_id',$id)->delete();
            foreach ($classes as $cid) {
                BookClassPlan::query()->insert([
                    'plan_id'    =>  $id,
                    'class_id'   =>  $cid
                ]);
            }
        }
        return $row;
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
     * 计划总数
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
     * 订单总数
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
     * 获取一个计划
     * @param $id
     * @return array
     */
    public static function getOnePlan($id) : array
    {
        self::setSelfModel(BookPlan::class);
        $model = self::getModelInstance();
        $data = $model->newQuery()
            ->join('book',function($query){
                $query->on('book.id','=','book_plan.id');
            })
            ->find($id);

        if(empty($data)) {
            return $data;
        }
        $data = $data->toArray();
        $data['classes'] = json_decode($data['classes'],true);
        return $data;
    }

    /**
     * 获取一个订单
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

    /**
     * @param $upDown
     * @return string
     */
    public static function getUpDownString($upDown) : string
    {
        if($upDown == 1) {
            return '上学期';
        }
        if($upDown == 2) {
            return '下学期';
        }

        return '全学年';
    }

    public function getOneBySn(string $sn) : array
    {
        $model = self::getModelInstance();
        $data = $model->newQuery()->where('sn','=', $sn)->limit(1)->get();
        $data = empty($data) ? [] : $data->toArray();
        return empty($data) ? [] : $data[0];
    }
}