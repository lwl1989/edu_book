<?php
/**
 * Created by PhpStorm.
 * User: limars
 * Date: 2018/4/12
 * Time: 22:34
 */

namespace App\Http\Controllers\Actions;


use App\Exceptions\ErrorConstant;
use App\Http\Controllers\Controller;
use App\Library\ArrayParse;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index');
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

        $filed = $request->input('field',false);
        if($filed !== false) {
            $fields = explode(',',$filed);
            if(is_array($fields)) {
                AdminService::setSelfListField($fields);
            }
        }

        $admin = AdminService::limit($conditions, $limit, $page, false, 1);
        return ['admin' => $admin];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        try {
            $params = ArrayParse::checkParamsArray(['account', 'role', 'status', 'name',
                'alias', 'department_id', 'tel', 'tel_ext', 'mobile', 'email', 'permissions'],
                $request->input());
        } catch (\Exception $exception) {
            return ['code' => $exception->getCode()];
        }

        $time = date('Y-m-d H:i:s', time());
        $params['permissions'] = json_encode($params['permissions']);
        $params['create_time'] = $params['update_time'] = $time;
        $admin = new AdminService();
        $admin->setAttr('password', bcrypt($params['account']));
        foreach ($params as $key => $value) {
            $admin->setAttr($key, $value);
        }

        return ['id' => $admin->create(), 'create_time' => $time, 'update_time' => $time];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        $id = $request->input('id', 0);
        if ($id == 0) {
            return [];
        }
        $admin = new AdminService();
        return ['row' => $admin->delete($id)];
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

        $params = ArrayParse::arrayCopy(['account', 'role', 'status', 'name', 'password',
            'alias', 'department_id', 'tel', 'tel_ext', 'mobile', 'email', 'permissions'], $request->input());
        if (empty($params)) {
            return [];
        }

        if (isset($params['permissions'])) {
            $params['permissions'] = json_encode($params['permissions']);
        }
        if (isset($params['password'])) {
            $params['password'] = bcrypt($params['password']);
        }

        $admin = new AdminService();
        foreach ($params as $key => $value) {
            $admin->setAttr($key, $value);
        }
        return ['row' => $admin->update($id)];
    }

    /**
     * @return array
     */
    public function count()
    {
        return ['count' => AdminService::count([], false, 1)];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function changePassword(Request $request) : array
    {
        try {
            $params = ArrayParse::checkParamsArray(['oldPassword', 'newPassword', 'confirmPassword'], $request->input());
        } catch (\Exception $exception) {
            return ['code' => $exception->getCode()];
        }

        if($params['newPassword'] != $params['confirmPassword']) {
            return ['code'=> ErrorConstant::PARAMS_ERROR];
        }

        return ['code'=> AdminService::changePassword($params) ? 0 : ErrorConstant::SYSTEM_ERR];
    }
}