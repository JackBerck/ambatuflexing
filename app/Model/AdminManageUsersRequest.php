<?php

namespace JackBerck\Ambatuflexing\Model;

class AdminManageUsersRequest
{
    public ?string $username = null;
    public ?string $email = null;
    public ?string $position = null;
    public ?int $page = 1;
    public ?int $limit = 50;
}