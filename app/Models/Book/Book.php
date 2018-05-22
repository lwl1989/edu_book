<?php

namespace App\Models\Book;



use App\Models\Model;

class Book extends Model
{
    protected $table = 'book';

    public static function outStock($bookId, int $num = 1)
    {
        self::query()->where('id','=',$bookId)->where('stock','>',$num)->decrement('stock',$num);
    }

}