<?php

namespace JackBerck\Ambatuflexing\Model;

class UserCommentPostRequest
{
    public int $userId;
    public int $postId;
    public string $comment;
}