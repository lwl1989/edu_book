<?php

namespace App\Entities;

use App\Exceptions\ErrorConstant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait EntityTrait
{
    public static $instance;
    protected $model;
    protected $attr;
    /**
     * @param $id
     * @return int
     */
    public function delete($id) : int
    {
        $model = self::getModelInstance();
        return $model->newQuery()->where('id',$id)->update(['deleted',1]);
    }
    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setAttr(string $key, string $value)
    {
        $this->attr[$key] = $value;
        return $this;
    }


    /**
     * @param $id
     * @return int
     */
    public function update($id) : int
    {
        $model = self::getModelInstance();
        return $model->newQuery()->where('id',$id)->update($this->attr);
    }

    /**
     * @return int
     */
    public function create() : int
    {
        $model = self::getModelInstance();
        foreach ($this->attr as $key => $value) {
            $model->$key = $value;
        }
        $model->save();
        return intval($model->id);
    }

    /**
     * @return string
     */
    public function getModel() : string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return $this
     */
    public function setModel(string $model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * @return Model
     */
    public static function getModelInstance() : Model
    {
        $model = self::getSelfModel();

        if(!empty($model)) {
            if(class_exists($model)) {
                return new $model();
            }
        }
        throw new ModelNotFoundException("class {$model} not found", ErrorConstant::SYSTEM_ERR);
    }

    /**
     * @param array $conditions
     * @param bool $deleted
     * @param int $status
     * @return Builder
     */
    protected static function _getQuery(array $conditions, bool $deleted = false, int $status = -1)
    {
        $service = self::_getInstance();
        $model = $service->getModelInstance();
        $conditions['deleted'] = ['=',$deleted ? 1 : 0];
        if($status != -1) {
            $conditions['status'] = ['=',$status];
        }

        $query = $model::query();
        foreach ($conditions as $field=>$operate) {
            if(is_array($operate)) {
                if(count($operate) == 2) {
                    $query->where($field, $operate[0], $operate[1]);
                }else{
                    $query->where($field, '=', $operate);
                }
            }else{
                $query->where($field,$operate);
            }
        }
        return $query;
    }

    /**
     * @param array $conditions
     * @param bool $deleted
     * @param int $status
     * @return int
     */
    public static function count(array $conditions, bool $deleted = false, int $status = -1) : int
    {
        $query = self::_getQuery($conditions, $deleted, $status);
        $count = $query->count();
        return intval($count);
    }

    /**
     * @param array $conditions
     * @param int $limit
     * @param int $page
     * @param bool $deleted
     * @param int $status
     * @return array
     */
    public static function limit(array $conditions, int $limit = 15, int $page = 1, bool $deleted = false, int $status = -1) : array
    {
        $query = self::_getQuery($conditions, $deleted, $status);
        $admin = $query->skip(($page-1)*$limit)
            ->take($limit)
            ->get()
            ->toArray();

        return $admin;
    }

    /**
     * @return EntityTrait
     */
    protected static function _getInstance()
    {
        if(self::$instance == null) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @return string
     */
    protected static function getSelfModel() : string
    {
        $service = self::_getInstance();
        return $service->getModel();
    }

}