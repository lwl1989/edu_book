<?php
/**
 * Created by PhpStorm.
 * User: limars
 * Date: 2018/4/12
 * Time: 22:43
 */

namespace App\Services;


use App\Models\Book\Book;

class BookService extends ServiceBasic
{
    protected $model = Book::class;
}