<?php

namespace JackBerck\Ambatuflexing\Model;

class UserUploadPostRequest
{
    public ?string $title = null;
    public ?string $content = null;
    public ?string $category = null;
    public ?int $authorId = null;
    public ?array $images = null;
}