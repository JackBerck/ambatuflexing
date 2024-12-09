<?php

namespace JackBerck\Ambatuflexing\Domain;

class Comment
{
    public int $id;
    public int $userId;
    public int $postId;
    public string $comment;
    public string $createdAt;
}