<?php

namespace App\Models;


class Model extends \Illuminate\Database\Eloquent\Model implements ModelExtInterface
{
    use ExtensionModelTrait;


    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();

        $builder = new QueryBuilder(
            $connection, $connection->getQueryGrammar(), $connection->getPostProcessor()
        );
        $builder->setModel($this);
        return $builder;
    }

    public function newCollection(array $item = [])
    {
        $collection = new Collection($item);
        $collection->setModel($this);
        return $collection;
    }
}