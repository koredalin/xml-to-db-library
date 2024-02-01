<?php

namespace Library\Entities;

class Book
{
    public function __construct(
        private int $id,
        public int $author_id,
        public string $name,
        public int $created_at,
        public int $updated_at
    ) {}


    public function getId(): int
    {
        return $this->id;
    }
}