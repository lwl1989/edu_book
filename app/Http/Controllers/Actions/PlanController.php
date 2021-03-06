<?php

namespace App\Http\Controllers\Actions;


use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Models\Book\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

class PlanController extends Controller
{

    /**
     * 总数
     * @param Request $request
     * @return array
     */
    public function count(Request $request)
    {
        return ['count' => BookService::countPlan([], false, 1)];
    }

    /**
     * 教材计划
     * @param Request $request
     * @return array
     */
    public function select(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $conditions = [];
        $keyword = $request->input('keyword', '');
        if(!empty($keyword)) {
            $conditions['keyword'] = $keyword;
        }
        $filed = $request->input('field',false);
        if($filed !== false) {
            $fields = explode(',',$filed);
            if(is_array($fields)) {
                BookService::setSelfListField($fields);
            }
        }

        $book = BookService::planLimit($conditions, $limit, $page, false, 1);

        return ['list' => $book];
    }

    /**
     * 获取一个计划的详情
     * @param Request $request
     * @return array
     */
    public function get(Request $request) : array
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return [];
        }

        return ['data'=>BookService::getOnePlan($id)];
    }

    /**
     * 创建一个计划
     * @param Request $request
     * @return array
     */
    public function create(Request $request) : array
    {
        try {
            $params = ArrayParse::checkParamsArray(['classes', 'notebook_num', 'book_id', 'number',
                'plan_year', 'up_down'],
                $request->input());
        } catch (\Exception $exception) {
            return ['code' => $exception->getCode()];
        }

        $params['classes'] = json_encode($params['classes']);
        $time = date('Y-m-d H:i:s', time());
        $book = new BookService();
        foreach ($params as $key => $value) {
            $book->setAttr($key, $value);
        }

        return ['id' => $book->createPlan(), 'create_time' => $time, 'update_time' => $time];
    }

    /**
     * 更新一个计划
     * @param Request $request
     * @return array
     */
    public function update(Request $request) : array
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return [];
        }

        $params = ArrayParse::arrayCopy(['classes', 'notebook_num', 'book_id', 'number',
            'plan_year', 'up_down'], $request->input());
        if (empty($params)) {
            return [];
        }

        $book = new BookService();
        foreach ($params as $key => $value) {
            $book->setAttr($key, $value);
        }
        return ['row' => $book->updatePlan($id)];
    }

    /**
     * 删除一个计划
     * @param Request $request
     * @return array
     */
    public function delete(Request $request) : array
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return [];
        }

        $book = new BookService();

        return ['row'=>$book->deletePlan($id)];
    }
}