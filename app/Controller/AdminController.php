<?php

namespace JackBerck\Ambatuflexing\Controller;

use JackBerck\Ambatuflexing\App\View;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\AdminManageUsersRequest;
use JackBerck\Ambatuflexing\Model\AdminManageUsersResponse;
use JackBerck\Ambatuflexing\Model\FindPostRequest;
use JackBerck\Ambatuflexing\Model\UserDeletePostRequest;
use JackBerck\Ambatuflexing\Repository\CommentRepository;
use JackBerck\Ambatuflexing\Repository\LikeRepository;
use JackBerck\Ambatuflexing\Repository\PostImageRepository;
use JackBerck\Ambatuflexing\Repository\PostRepository;
use JackBerck\Ambatuflexing\Repository\SessionRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;
use JackBerck\Ambatuflexing\Service\PostService;
use JackBerck\Ambatuflexing\Service\SessionService;
use JackBerck\Ambatuflexing\Service\UserService;

class AdminController
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
        $commentRepository = new CommentRepository($connection);

        $this->postService = new PostService($postRepository, $postImageRepository, $userRepository, $likeRepository, $commentRepository);
    }

    public function dashboard(): void
    {
        $user = $this->sessionService->current();

        View::render('Admin/dashboard', [
            "title" => "Dashboard Admin",
            "user" => (array)$user
        ]);
    }

    function managePosts(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Search Post'
        ];

        if ($user != null) {
            $model['user'] = $user;
        }

        $req = new FindPostRequest();
        $req->title = $_GET['title'] ?? null;
        $req->category = $_GET['category'] ?? null;
        $req->userId = isset($_GET['userId']) && (int)$_GET['userId'] ? (int)$_GET['userId'] : null;
        $req->limit = 50;
        $req->page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $this->postService->search($req);

        View::render('Admin/managePosts', $model);
    }

    function updatePost($postId): void
    {
        $user = $this->sessionService->current();

        $model = [
            'user' => (array)$user,
        ];

        try {
            $details = $this->postService->details($postId);

            $model['post'] = (array)$details->post;
            $model['author'] = $details->author;
            $model['authorPhoto'] = $details->authorPhoto;
            $model['authorPosition'] = $details->authorPosition;
            $model['images'] = $details->images;
            $model['title'] = $details->post->title;

            View::render('Admin/updatePost', $model);
        } catch (ValidationException $exception) {
            View::redirect('/admin/dashboard/manage-posts');
        }
    }

    public function manageUsers(): void
    {
        $user = $this->sessionService->current();
        $model = ['user' => (array)$user, 'title' => "Manage Users"];

        $req = new AdminManageUsersRequest();
        $req->limit = 50;
        $req->page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $req->email = $_POST['email'] ?? null;
        $req->username = $_POST['username'] ?? null;
        $req->position = $_POST['position'] ?? null;

        $res = $this->userService->manage($req);

        $model['manageUsers'] = $res->users;
        $model["totalUsers"] = $res->totalUsers;
        View::render('Admin/manageUsers', $model);
    }

}