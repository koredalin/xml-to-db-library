<?php

namespace Library\Entities\Factories;

use Library\Entities\Author;

class AuthorFactory
{
    public static function createFromDb(array $data): Author
    {
        return new Author(
            (int) $data['id'],
            trim($data['name']),
            (int) $data['created_at'],
            (int) $data['updated_at'],
        );
    }
}