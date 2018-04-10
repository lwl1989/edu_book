<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Query\Builder as QueryBuilder;

class User extends Authenticatable
{
    use Notifiable;
    public $table = 'student';
    public function __construct(array $attributes = [])
    {
        $type = request()->input('type');

        $this->_getUserType($type);

        parent::__construct($attributes);
    }

    public function newQuery()
    {
        $query = $this->newBaseQueryBuilder();
        $builder = new class($query) extends Builder
        {
            public function __construct(QueryBuilder $query)
            {

                parent::__construct($query);
            }

            /**
             * Execute the query and get the first result.
             *
             * @param  array $columns
             * @return \Illuminate\Database\Eloquent\Model|object|static|null
             */
            public function first($columns = ['*'])
            {
                /** @var Model $user */
                $user = $this->take(1)->get($columns)->first();
                //從別的模型查數據  設置到這個模型裏面
                if ($user != null) {
                    //拓展user的其他屬性
                    $user->setAttribute('acl', ['dsadas', 'dsadas', 'dsada']);
                }
                return $user;
            }

        };
        $builder->setModel($this);
        return $builder;
    }

    public function getAuthIdentifierName()
    {
        return 'id';
    }

    private function _getUserType(string $type)
    {
        switch ($type) {
            case 'student':
                $this->table = 'student';
                break;
            case 'teacher':
                $this->table = 'teacher';
                break;
            case 'admin':
                $this->table = 'admin';
                break;
            default:
                $this->table = 'student';
        }
    }

    public function __get($name)
    {
        $value = $this->getAttributeValue($name);
        if (is_null($value)) {
            return '';
        }
        if (!is_string($value)) {
            return json_encode($value);
        }
        return $value;
    }
}