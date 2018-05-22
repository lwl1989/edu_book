<?php


namespace App\Models;



use App\Library\FieldFormat;

class Collection extends \Illuminate\Database\Eloquent\Collection
{
    public $model = null;
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

    public function toArray()
    {
        $model = $this->getModel();
        if($model->getEnableFormat()) {

            $array = parent::toArray();
            $array = $this->_format($array);

            return $array;
        }else{
            return parent::toArray();
        }
    }

    private function _format(array $array) : array
    {
        $format = $this->getModel()->getFormat();
        array_walk($array, function (&$value, $key) use ($format){

            if(is_array($value) and is_numeric($key)) {
                $value = $this->_format($value);
            }

            if(array_key_exists($key, $format)) {
                if(is_string($format[$key])) {
                    $method = $format[$key];
                    $value = call_user_func([FieldFormat::class, $method], $value);
                }else if(is_array($format[$key])) {
                    $method = array_shift($format[$key]);
                    $value = call_user_func([FieldFormat::class, $method], $value, ...$format[$key]);
                }else if(is_callable($format[$key])) {
                    $value = $format[$key]($value);
                }
            }

        });
        return $array;
    }

}