<?php

namespace JackBerck\Ambatuflexing\Model;

class UserUpdatePostRequest
{
    public ?string $title = null;
    public ?string $content = null;
    public ?string $category = null;
    public ?int $userId = null;
    public ?int $postId = null;
}