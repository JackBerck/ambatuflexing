<?php

namespace JackBerck\Ambatuflexing\Model;

class FindPostRequest
{
    public ?string $title = null;
    public ?string $category = null;
    public ?int $userId = null;
    public int $page = 1;
    public int $limit = 20;
}