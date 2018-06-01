<?php

namespace App\Http\Controllers\Actions;


use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Models\Book\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private function _buildCondition(Request $request): array
    {
        $condition = [];

        if (strpos($request->getRequestUri(), '/api') === 0) {
            $condition['online_time'] = ['<', date('Y-m-d H:i:s')];
            $condition['offline_time'] = ['>', date('Y-m-d H:i:s')];
        } else {
            $params = $request->query();
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                    switch ($key) {
                        case strpos($key, 'time') !== false:
                            if (!empty($value)) {
                                $time = explode(',', $value);
                                if (count($time) === 2) {

                                    $condition[$key] = ['between', [
                                        date('Y-m-d H:i:s', substr($time[0], 0, 10)),
                                        date('Y-m-d H:i:s', substr($time[1], 0, 10))
                                    ]
                                    ];
                                }
                            }
                            break;
                        case 'page':
                        case 'limit':
                            break;
                        case 'title':
                            if (!empty($value)) {
                                $condition['keyword'] = ['like', $value];
                            }
                            break;
                        default:
                            if (!empty($value)) {
                                $condition[$key] = $value;
                            }
                    }

                }
            }
        }
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
        return $condition;
    }

    public function get(Request $request) : array
    {
        $id = $request->input('id', 0);

        $sn = $request->input('sn', '');
        if ($id == 0 and $sn == '') {
            return [];
        }

        $book = new BookService();

        if($id != 0) {
            return ['data'=>$book->getOne($id)];
        }else{
            return ['data'=>$book->getOneBySn($sn)];
        }
    }
    /**
     * @param Request $request
     * @return array
     */
    public function select(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $book = BookService::limit($this->_buildCondition($request), $limit, $page, false, 1);
        return ['list' => $book];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        try {
            $params = ArrayParse::checkParamsArray(['name', 'sn', 'author', 'company',
                 'cost', 'stock', 'reserve'],
                $request->input());
        } catch (\Exception $exception) {
            return ['code' => $exception->getCode()];
        }

        $time = date('Y-m-d H:i:s', time());
        $book = new BookService();
        foreach ($params as $key => $value) {
            $book->setAttr($key, $value);
        }

        return ['id' => $book->create(), 'create_time' => $time, 'update_time' => $time];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return ['code'=>1];
        }
        $book = new BookService();
        return ['row' => $book->delete($id)];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function update(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return [];
        }

        $params = ArrayParse::arrayCopy(['name', 'sn', 'author', 'company',
            'cost', 'stock', 'reserve'], $request->input());
        if (empty($params)) {
            return [];
        }

        $book = new BookService();
        foreach ($params as $key => $value) {
            $book->setAttr($key, $value);
        }
        return ['row' => $book->update($id)];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function count(Request $request)
    {
        return ['count' => BookService::count($this->_buildCondition($request), false, 1)];
    }


}