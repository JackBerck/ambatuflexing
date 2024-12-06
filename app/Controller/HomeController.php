<?php

namespace JackBerck\Ambatuflexing\Controller;

use JackBerck\Ambatuflexing\App\View;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\UserUploadPostRequest;
use JackBerck\Ambatuflexing\Repository\PostImageRepository;
use JackBerck\Ambatuflexing\Repository\PostRepository;
use JackBerck\Ambatuflexing\Repository\SessionRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;
use JackBerck\Ambatuflexing\Service\PostService;
use JackBerck\Ambatuflexing\Service\SessionService;

class HomeController
{

    private SessionService $sessionService;
    private PostService $postService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $sessionRepository = new SessionRepository($connection);
        $userRepository = new UserRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);

        $postRepository = new PostRepository($connection);
        $postImageRepository = new PostImageRepository($connection);
        $userRepository = new UserRepository($connection);

        $this->postService = new PostService($postRepository, $postImageRepository, $userRepository);
    }


    function index(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Devflex',
        ];

        if ($user != null) {
            $model['user'] = $user;
        }

        View::render('home/index', $model);
    }

    function about(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Home Page',
        ];

        if ($user != null) {
            $model['user'] = $user;
        }

        View::render('home/about', $model);
    }

    function detail($postId): void
    {
        $user = $this->sessionService->current();
        $model = [];
        if ($user != null) {
            $model['user'] = $user;
        }

        try {
            $details = $this->postService->details($postId);
            $model['post'] = (array)$details->post;
            $model['author'] = $details->author;
            $model['authorPhoto'] = $details->authorPhoto;
            $model['authorPosition'] = $details->authorPosition;
            $model['images'] = $details->images;
            View::render('home/detail', $model);
        } catch (ValidationException $exception) {
            View::redirect('/');
        }
    }

    function upload(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Upload Your Content',
        ];
        if ($user != null) {
            $model['user'] = (array)$user;
        }
        View::render('home/upload', $model);
    }

    function postUpload(): void
    {
        $user = $this->sessionService->current();

        $req = new UserUploadPostRequest();
        $req->title = $_POST['title'] ?? null;
        $req->content = $_POST['content'] ?? null;
        $req->category = $_POST['category'] ?? null;
        $req->authorId = $user->id;

        if (isset($_FILES['images'])) {
            $req->images = $_FILES['images'];
        } else {
            $req->images = [];
        }

        try {
            $this->postService->upload($req);

            View::redirect('/');
        } catch (ValidationException $exception) {
            View::redirect('/upload');
        }
    }

    function search(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Search Post'
        ];

        if ($user != null) {
            $model['user'] = $user;
        }

        try {
            View::render('home/search', $model);
        } catch (ValidationException $exception) {
            view::redirect('/search');
        }
    }
}
