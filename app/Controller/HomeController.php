<?php

namespace JackBerck\Ambatuflexing\Controller;

use JackBerck\Ambatuflexing\App\Flasher;
use JackBerck\Ambatuflexing\App\View;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\FindPostRequest;
use JackBerck\Ambatuflexing\Model\UserUploadPostRequest;
use JackBerck\Ambatuflexing\Repository\CommentRepository;
use JackBerck\Ambatuflexing\Repository\LikeRepository;
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
        $likeRepository = new LikeRepository($connection);
        $commentRepository = new CommentRepository($connection);

        $this->postService = new PostService($postRepository, $postImageRepository, $userRepository, $likeRepository, $commentRepository);
    }

    function index(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Devflex',
        ];

        if ($user != null) {
            $model['user'] = (array)$user;
        }

        $req = new FindPostRequest();
        $req->limit = 50;
        $req->page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $res = $this->postService->search($req);
        $model['posts'] = $res->posts;
        $model['total'] = $res->totalPost;

        View::render('home/index', $model);
    }

    function about(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Home Page',
        ];

        if ($user != null) {
            $model['user'] = (array)$user;
        }

        View::render('home/about', $model);
    }

    function detail($postId): void
    {
        $user = $this->sessionService->current();
        $model = [];
        if ($user != null) {
            $model['user'] = (array)$user;
        }

        try {
            $details = $this->postService->details($postId);
            $model['post'] = (array)$details->post;
            $model['author'] = $details->author;
            $model['authorPhoto'] = $details->authorPhoto;
            $model['authorPosition'] = $details->authorPosition;
            $model['images'] = $details->images;
            $model['comments'] = $details->comments;
            $model["likeCount"] = $details->likeCount;
            $model["commentCount"] = $details->commentCount;
            $model['title'] = $details->post->title;
            View::render('home/detail', $model);
        } catch (ValidationException $exception) {
            Flasher::set("Error", $exception->getMessage(), "error");
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
        var_dump($req);
        try {
            $this->postService->upload($req);

            Flasher::set("Success", "Post Uploaded");
            View::redirect('/');
        } catch (ValidationException $exception) {
            Flasher::set("Error", "Post " . $exception->getMessage(), "error");
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
            $model['user'] = (array)$user;
        }

        $req = new FindPostRequest();
        $req->title = $_GET['title'] ?? null;
        $req->category = $_GET['category'] ?? null;
        $req->limit = 50;
        $req->page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int)$_GET['page'] : 1;

        $this->postService->search($req);

        View::render('home/search', $model);
    }

    public function error(): void
    {
        $user = $this->sessionService->current();
        $model = [
            'title' => 'Error 404',
        ];
        if ($user != null) {
            $model['user'] = (array)$user;
        }
        View::render("Home/error", $model);
    }
}
