<?php

namespace App\Models;


use App\Models\Log\Logs;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class QueryBuilder extends Builder
{
    //set Array class Is need Log  Admin::class
    public $model = null;
    const NEED_LOG = [];

    /**
     * @overwrite change collect
     * @param array $columns
     * @return Collection
     */
    public function get($columns = ['*'])
    {
        $original = $this->columns;

        if (is_null($original)) {
            $this->columns = $columns;
        }

        $results = $this->processor->processSelect($this, $this->runSelect());

        $this->columns = $original;
        return $this->collect($results);
    }

    /**
     * @overwrite add log
     * @param array $values
     * @return bool
     */
    public function insert(array $values)
    {
        $id = parent::insert($values);
        $this->log($id, '新增',$this->grammar->compileInsert($this, $values));
        return $id;
    }

    /**
     * @overwrite add log
     * @param array $values
     * @return int
     */
    public function update(array $values)
    {
        $num = parent::update($values);
        $this->log($num, '更新',$this->grammar->compileUpdate($this, $values));
        return $num;
    }

    /**
     * @overwrite add log
     * @param null $id
     * @return int
     */
    public function delete($id = null)
    {
        $this->log($id, '刪除', $this->grammar->compileDelete($this));
        return parent::delete($id);
    }

    /**
     * 設置成自定義model
     * @param ModelExtInterface $model
     */
    public function setModel(ModelExtInterface $model)
    {
        $this->model = $model;
    }

    /**
     * 設置成自定義model
     * @return null|ModelExtInterface
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * db統一封裝日志  增刪改 保存SQL 和 remark
     * @param null $id
     * @param $sql
     * @param $operate
     */
    private function log($id = null, string $operate, string $sql = '')
    {
        $model = $this->getModel();
        if ($model instanceof ModelExtInterface and $model->needLog()) {
            $data = [];

            $sql =  empty($sql) ? $this->toSql() : $sql;
            $data['remark'] = '';
            $data['operator_id'] = Auth::id(); //who operate
            if($id != null) {
                $data['operate_msg'] = sprintf("ID:爲 %s 的用戶對 %s 表進行了 %s 操作, 數據ID爲: %s", $data['operator_id'], $this->from, $operate, $id);
            }else {
                $data['operate_msg'] = sprintf("ID:爲 %s 的用戶對 %s 表進行了 %s 操作", $data['operator_id'], $this->from, $operate);
            }
            $data['sql'] = $sql;
            Logs::query()->insert($data);
        }
    }

    private function collect($item = null) : Collection
    {
        return new Collection($item);
    }
}