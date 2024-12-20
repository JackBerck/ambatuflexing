<?php

namespace JackBerck\Ambatuflexing\Middleware;

use JackBerck\Ambatuflexing\App\View;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Repository\SessionRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;
use JackBerck\Ambatuflexing\Service\SessionService;

class MustLoginMiddleware implements Middleware
{
    private SessionService $sessionService;

    public function __construct()
    {
        $sessionRepository = new SessionRepository(Database::getConnection());
        $userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function before(): void
    {
        $user = $this->sessionService->current();
        if ($user == null) {
            View::redirect('/login');
        }
    }
}
