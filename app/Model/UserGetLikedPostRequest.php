<?php

namespace JackBerck\Ambatuflexing\Model;

class UserGetLikedPostRequest
{
    public ?int $userId = null;
    public int $page = 1;
    public int $limit = 50;
}