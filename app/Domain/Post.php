<?php

namespace JackBerck\Ambatuflexing\Domain;

class Post
{
    public int $id;
    public string $title;
    public string $content;
    public string $category;
    public string $createdAt;
    public string $updatedAt;
    public int $authorId;
}