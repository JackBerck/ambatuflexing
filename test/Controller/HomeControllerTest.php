<?php

namespace JackBerck\Ambatuflexing\Controller;

use PHPUnit\Framework\TestCase;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Domain\Session;
use JackBerck\Ambatuflexing\Domain\User;
use JackBerck\Ambatuflexing\Repository\SessionRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;
use JackBerck\Ambatuflexing\Service\SessionService;

class HomeControllerTest extends TestCase
{
    private HomeController $homeController;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    protected function setUp(): void
    {
        $this->homeController = new HomeController();
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();
    }

    public function testGuest()
    {
        $this->homeController->index();

        $this->expectOutputRegex("[Login Management]");
    }

    public function testUserLogin()
    {
        $user = new User();
        $user->id = "eko";
        $user->name = "Eko";
        $user->password = "rahasia";
        $this->userRepository->save($user);

        $session = new Session();
        $session->id = uniqid();
        $session->userId = $user->id;
        $this->sessionRepository->save($session);

        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;

        $this->homeController->index();

        $this->expectOutputRegex("[Hello Eko]");
    }
}
