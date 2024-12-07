<?php

namespace JackBerck\Ambatuflexing\Controller;

use JackBerck\Ambatuflexing\App\View;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\UserGetLikedPostRequest;
use JackBerck\Ambatuflexing\Model\UserLoginRequest;
use JackBerck\Ambatuflexing\Model\UserPasswordUpdateRequest;
use JackBerck\Ambatuflexing\Model\UserProfileUpdateRequest;
use JackBerck\Ambatuflexing\Model\UserRegisterRequest;
use JackBerck\Ambatuflexing\Repository\LikeRepository;
use JackBerck\Ambatuflexing\Repository\PostImageRepository;
use JackBerck\Ambatuflexing\Repository\PostRepository;
use JackBerck\Ambatuflexing\Repository\SessionRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;
use JackBerck\Ambatuflexing\Service\PostService;
use JackBerck\Ambatuflexing\Service\SessionService;
use JackBerck\Ambatuflexing\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;
    private PostService $postService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $this->userService = new UserService($userRepository);

        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $postRepository = new PostRepository($connection);
        $postImageRepository = new PostImageRepository($connection);
        $likeRepository = new LikeRepository($connection);
        $this->postService = new PostService($postRepository, $postImageRepository, $userRepository, $likeRepository);
    }

    public function register(): void
    {
        View::render('User/register', [
            'title' => 'Register',
        ]);
    }

    public function postRegister(): void
    {
        $request = new UserRegisterRequest();
        $request->email = $_POST['email'];
        $request->username = $_POST['username'];
        $request->password = $_POST['password'];

        try {
            $this->userService->register($request);
            View::redirect('/login');
        } catch (ValidationException $exception) {
            View::redirect('/register');
        }
    }

    public function login(): void
    {
        View::render('User/login', [
            "title" => "Login"
        ]);
    }

    public function postLogin(): void
    {
        $request = new UserLoginRequest();
        $request->email = $_POST['email'];
        $request->password = $_POST['password'];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->id);
            View::redirect('/');
        } catch (ValidationException $exception) {
            View::redirect('/login');
        }
    }

    public function logout(): void
    {
        $this->sessionService->destroy();
        View::redirect("/");
    }

    public function dashboard(): void
    {
        $user = $this->sessionService->current();

        View::render('User/dashboard', [
            "title" => "Dashboard",
            "user" => (array)$user
        ]);
    }

    public function putUpdateProfile(): void
    {
        $user = $this->sessionService->current();
        $redirect = $user->isAdmin == 'user' ? '/user/dashboard' : '/admin/dashboard';

        // Mengambil data dari PUT request
        parse_str(file_get_contents("php://input"), $_PUT);

        $request = new UserProfileUpdateRequest();
        $request->id = $user->id;
        $request->username = $_PUT['username'] ?? null;
        $request->position = $_PUT['position'] ?? null;
        $request->bio = $_PUT['bio'] ?? null;
        $request->photo = $_FILES['profile']['tmp_name'] != "" ? $_FILES['profile'] : null;

        try {
            $this->userService->updateProfile($request);
            View::redirect($redirect);
        } catch (ValidationException $exception) {
            View::redirect($redirect);
        }
    }

    public function patchUpdatePassword(): void
    {
        $user = $this->sessionService->current();
        $redirect = $user->isAdmin == 'user' ? '/user/dashboard' : '/admin/dashboard';

        parse_str(file_get_contents("php://input"), $_PATCH);

        $request = new UserPasswordUpdateRequest();
        $request->id = $user->id;
        $request->oldPassword = $_PATCH['oldPassword'];
        $request->newPassword = $_PATCH['newPassword'];

        try {
            $this->userService->updatePassword($request);
            View::redirect($redirect);
        } catch (ValidationException $exception) {
            View::redirect($redirect);
        }
    }

    public function likedPosts(): void
    {
        $user = $this->sessionService->current();

        $request = new UserGetLikedPostRequest();
        $request->userId = $user->id;
        $request->page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $request->limit = 20;

        $response = $this->postService->likedPost($request);

        $model = [
            'title' => 'Liked Posts',
            'user' => (array)$user,
            'posts' => $response->likedPost,
            'total' => $response->totalPost,
        ];

        View::render('User/likedPosts', $model);
    }
}
