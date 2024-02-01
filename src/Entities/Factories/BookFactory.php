<?php

namespace Library\Entities\Factories;

use Library\Entities\Book;

class BookFactory
{
    public static function createFromDb(array $data): Book
    {
        return new Book(
            (int) $data['id'],
            (int) $data['author_id'],
            trim($data['name']),
            (int) $data['created_at'],
            (int) $data['updated_at'],
        );
    }
}