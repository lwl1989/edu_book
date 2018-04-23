<?php

namespace App\Http\Controllers\Actions;


use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Models\Book\Book;
use App\Services\BookService;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function get(Request $request) : array
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return [];
        }

        $book = new BookService();

        return ['data'=>$book->getOne($id)];
    }
    /**
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

        $book = BookService::limit($conditions, $limit, $page, false, 1);
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
     * @return array
     */
    public function count()
    {
        return ['count' => BookService::count([], false, 1)];
    }


}