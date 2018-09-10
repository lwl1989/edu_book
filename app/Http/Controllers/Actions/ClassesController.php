<?php
/**
 * Created by PhpStorm.
 * User: limars
 * Date: 2018/4/12
 * Time: 22:32
 */

namespace App\Http\Controllers\Actions;


use App\Exceptions\ErrorConstant;
use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Services\ClassesService;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * 创建一个班级
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        try {
            $params = ArrayParse::checkParamsArray(['name', 'excepted_count'],
                $request->input());
        } catch (\Exception $exception) {
            return ['code' => $exception->getCode()];
        }
        $params['student_count'] = 0;

        $time = date('Y-m-d H:i:s', time());
        $book = new ClassesService();
        foreach ($params as $key => $value) {
            $book->setAttr($key, $value);
        }

        return ['id' => $book->create(), 'create_at' => $time, 'update_at' => $time];
    }

    /**
     * 获取本班级的领书用户
     * @param Request $request
     * @return array
     */
    public function receiveRecord(Request $request) : array
    {
        $classId = $request->get('cid',0);

        if($classId == 0) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }
        $condition = [
            'class_id'=>$classId,
        ];
        $year = $request->get('year', '');
        if(!empty($year)) {
            $arr = explode('_', $year);
            $year = $arr[0];
            $upDown = $arr[1] ?? 0;
            $condition['year'] = $year;
            $condition['up_down'] = $upDown;
        }


        return ['list'=>ClassesService::receivedLimit($condition,false, -1)];
    }

    /**
     * 获取本班级的哪些书已经领了
     * @param Request $request
     * @return array
     */
    public function receiveBookRecord(Request $request) : array
    {
        $classId = $request->get('cid',0);

        if($classId == 0) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        return ['list'=>ClassesService::receivedBookLimit(['class_id'=>[$classId]],false, -1)];
    }

    /**
     * 预留功能  哪些学生已经支付了
     * @param Request $request
     * @return array
     */
    public function payRecord(Request $request) : array
    {
        $classId = $request->get('cid',0);
        if($classId == 0) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        return ['list'=>ClassesService::payLimit(['class_id'=>[$classId]],100,1,false, -1)];
    }

    /**
     * 更新班级
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        if(!($params = $this->_check($request, ['name', 'excepted_count','student_count']))){
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        $id = $params[0];
        $params = $params[1];

        $class = new ClassesService();
        foreach ($params as $key => $value) {
            $class->setAttr($key, $value);
        }

        $row = $class->update($id);
        return ['row'=>$row];
    }

    /**
     * 删除班级
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return ['code'=>1];
        }

        $class = new ClassesService();

        return ['code'=>$class->delete($id) >= 0 ? 0 : 1 ];
    }

    /**
     * 获取班级列表
     * @param Request $request
     * @return array
     */
    public function select(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        //todo: 获取 year up down  *计划 *需要的书本价格  * 人数  统计起来   获取实际本班需要缴纳的金额
        $class = ClassesService::limit([], $limit, $page, false, -1);
        return ['list' => $class];
    }

    /**
     * 获取班级总数
     * @param Request $request
     * @return array
     */
    public function count(Request $request)
    {
        $class = ClassesService::count([], false, -1);
        return ['list' => $class];
    }

    /**
     * 获取一个班级详情
     * @param Request $request
     * @return array
     */
    public function get(Request $request)
    {
        $id = $this->_checkId($request);

        $class = new ClassesService();

        return ['id'=>$id,'data'=>$class->getOne($id)];
    }

    private function _check(Request $request, array $keys) : array
    {
        $id = $this->_checkId($request);
        if ($id <= 0) {
                return [];
        }

        $params = ArrayParse::arrayCopy($keys, $request->input());

        if(empty($params)) {
            return [];
        }

        return [$id,$params];
    }

    private function _checkId(Request $request) : int
    {
        return $request->input('id', 0);
    }
}