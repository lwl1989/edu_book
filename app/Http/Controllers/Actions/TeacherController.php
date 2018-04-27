<?php

namespace App\Http\Controllers\Actions;


use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Services\Users\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
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
                $book = new TeacherService();
                foreach ($params as $key => $value) {
                        $book->setAttr($key, $value);
                }

                return ['id' => $book->create(), 'create_at' => $time, 'update_at' => $time];
        }

        public function update(Request $request)
        {
                if($params = $this->_check($request, ['name', 'excepted_count','student_count'])){
                        return ['code'=>1];
                }

                $id = $params[0];
                $params = $params[1];

                $class = new TeacherService();
                foreach ($params as $key => $value) {
                        $class->setAttr($key, $value);
                }

                $row = $class->update($id);
                return ['row'=>$row];
        }

        public function delete(Request $request)
        {
                $id = $request->input('id', 0);
                if ($id == 0) {
                        return ['code'=>1];
                }

                $class = new TeacherService();

                return ['code'=>$class->delete($id) >= 0 ? 0 : 1 ];
        }

        public function select(Request $request)
        {
                $page = $request->input('page', 1);
                $limit = $request->input('limit', 10);

                $class = TeacherService::limit([], $limit, $page, false, -1);
                return ['list' => $class];
        }

        public function count(Request $request)
        {
                $class = TeacherService::count([], false, -1);
                return ['list' => $class];
        }

        public function get(Request $request)
        {
                $id = $this->_checkId($request);

                $class = new TeacherService();

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