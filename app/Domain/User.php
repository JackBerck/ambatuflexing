<?php

namespace JackBerck\Ambatuflexing\Domain;

class User
{
    public int $id;
    public string $email;
    public string $username;
    public string $password;
    public ?string $position = null;
    public ?string $bio = null;
    public ?string $photo = null;
    public string $isAdmin;
    public string $createdAt;
}
