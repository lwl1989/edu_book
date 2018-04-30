<?php

namespace App\Http\Controllers\Actions;


use App\Exceptions\ErrorConstant;
use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Services\Users\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function batchPay(Request $request) : array
    {
        $classId = $request->post('cid',0);
        $students = $request->post('students',[]);
        if($classId === 0 or empty($students)) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        return ['code'=> StudentService::batchPay($classId, $students) ? 0 : 1];
    }

    public function received(Request $request) : array
    {
        $id = $request->get('uid',0);
        if($id == 0) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 0);

        $student = new StudentService();
        return ['list'=>$student->receiveLimit(['student_id'=>$id], $limit, $page, false, -1)];
    }

    public function doReceive(Request $request) : array
    {
        $id = $request->post('id',0);
        if($id == 0) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }
        $books = $request->post('books',[]);
        if(empty($books) or !is_array($books)) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        $student = new StudentService();
        //$student->doReceive($id, $books);
        return ['code'=>$student->doReceive($id, $books) ? 0 : 1];
    }

    public function getBookHasReceived(Request $request): array
    {
        $sn = $request->get('sn', '');
        if ($sn === '') {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }
        $userId = $request->get('uid',0);
        if ($userId === 0) {
            return ['code'=>ErrorConstant::PARAMS_ERROR];
        }

        $student = new StudentService();
        $studentBook = $student->getOneBySnAndCheckReceive($sn,$userId);
        if(!empty($studentBook)){
            if($studentBook['received'] === true) {
                return ['code'=>ErrorConstant::STUDENT_RECEIVED];
            }
        }
        return ['data' => $studentBook];
    }


    public function create(Request $request): array
    {
        try {
            $params = ArrayParse::checkParamsArray(['name', 'class_id', 'student_num'],
                $request->input());
        } catch (\Exception $exception) {
            return ['code' => $exception->getCode()];
        }

        $time = date('Y-m-d H:i:s', time());
        $book = new StudentService();
        foreach ($params as $key => $value) {
            $book->setAttr($key, $value);
        }

        return ['id' => $book->create(), 'create_at' => $time, 'update_at' => $time];
    }

    public function update(Request $request): array
    {
        if (!($params = $this->_check($request, ['name', 'class_id', 'student_num']))) {
            return ['code' => 1];
        }

        $id = $params[0];
        $params = $params[1];

        $class = new StudentService();
        foreach ($params as $key => $value) {
            $class->setAttr($key, $value);
        }

        $row = $class->update($id);
        return ['row' => $row];
    }

    public function delete(Request $request): array
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return ['code' => 1];
        }

        $class = new StudentService();

        return ['code' => $class->delete($id) >= 0 ? 0 : 1];
    }

    public function select(Request $request): array
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $class = StudentService::limit([], $limit, $page, false, -1);
        return ['list' => $class];
    }

    public function count(Request $request)
    {
        $class = StudentService::count([], false, -1);
        return ['list' => $class];
    }

    public function get(Request $request)
    {
        $id = $this->_checkId($request);

        $class = new StudentService();

        return ['id' => $id, 'data' => $class->getOne($id)];
    }

    private function _check(Request $request, array $keys): array
    {
        $id = $this->_checkId($request);
        if ($id <= 0) {
            return [];
        }

        $params = ArrayParse::arrayCopy($keys, $request->input());

        if (empty($params)) {
            return [];
        }

        return [$id, $params];
    }

    private function _checkId(Request $request): int
    {
        return $request->input('id', 0);
    }
}