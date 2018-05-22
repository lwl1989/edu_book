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
        $this->log($id);
        return $id;
    }

    /**
     * @overwrite add log
     * @param array $values
     * @return int
     */
    public function update(array $values)
    {
        $this->log();
        return parent::update($values);
    }

    /**
     * @overwrite add log
     * @param null $id
     * @return int
     */
    public function delete($id = null)
    {
        $this->log();
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
     */
    private function log($id = null)
    {
        $model = $this->getModel();
        if ($model instanceof ModelExtInterface and $model->needLog()) {
            $data = [];
//            if ($id !== null) {
//                $data['id'] = $id;
//            }
            $sql = $this->toSql();
            $data['remark'] = $model->getLogRemark();
            $data['operator_id'] = Auth::id(); //who operate
            $data['sql'] = $sql;
            Logs::query()->insert($data);
        }
    }

    private function collect($item = null) : Collection
    {
        return new Collection($item);
    }
}