<?php

namespace JackBerck\Ambatuflexing\Model;

class AdminUpdatePasswordRequest
{
    public ?string $newPassword = null;
    public ?int $userId = null;
}