<?php


namespace App\Services;


use App\Exceptions\ErrorConstant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ServiceTrait
{
    public static $instance;
    protected $model;
    protected $attr;
    protected $listField = ['*'];
    protected $detailField = ['*'];
    protected $searchField = 'name';

    public function getOne($id) : array
    {
        $model = self::getModelInstance();
        $data = $model->newQuery()->find($id, self::getSelfDetailField());
        return empty($data) ? [] : $data->toArray();
    }
    /**
     * @param $id
     * @return int
     */
    public function delete($id) : int
    {
        $model = self::getModelInstance();
        return $model->newQuery()->where('id',$id)->update(['deleted'=>1]);
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
     * @param string $key
     * @return string|array
     */
    public function getAttr(string $key = '')
    {
        if(!empty($key)) {
            return $this->attr[$key]??'';
        }
        return $this->attr;
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
        if($model->save()) {
            return intval($model->id);
        }
        return -1;
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
        $table = $model->getTable();
        foreach ($conditions as $field=>$operate) {
            $field = $table.'.'.$field;
            if($field == $table.'.keyword') {
                $field = $table.'.'.$service->searchField;
                $query->where($field, 'like', '%'.$operate.'%');
                continue;
            }
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
        $list = $query->skip(($page-1)*$limit)
            ->take($limit)
            ->get(self::getSelfListField())
            ->toArray();

        return $list;
    }

    /**
     * @return ServiceTrait
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

    /**
     * @param  string
     */
    protected static function setSelfModel(string $model)
    {
        $service = self::_getInstance();
        $service->setModel($model);
    }

    /**
     * @return array
     */
    public static function getSelfListField() : array
    {
        $service = self::_getInstance();
        $fields = $service->listField;

        if(empty($fields)) {
            return ['*'];
        }
        return $fields;
    }

    /**
     * @return array
     */
    public static function getSelfDetailField() : array
    {
        $service = self::_getInstance();
        $fields = $service->detailField;

        if(empty($fields)) {
            return ['*'];
        }
        return $fields;
    }


    /**
     * @param array $fields
     */
    public static function setSelfListField(array $fields)
    {
        $service = self::_getInstance();
        $service->listField = $fields;
    }

    /**
     * @param array $fields
     */
    public static function setSelfDetailField(array $fields)
    {
        $service = self::_getInstance();
        $service->detailField = $fields;
    }
}