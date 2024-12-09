<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JackBerck\Ambatuflexing\App\Router;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Controller\HomeController;
use JackBerck\Ambatuflexing\Controller\UserController;
use JackBerck\Ambatuflexing\Middleware\MustNotLoginMiddleware;
use JackBerck\Ambatuflexing\Middleware\MustLoginMiddleware;

Database::getConnection('prod');

// Home
Router::add("GET", "/", HomeController::class, "index");
Router::add("GET", "/about", HomeController::class, "about");
Router::add("GET", "/post/([0-9]*)", HomeController::class, "detail");
Router::add("POST", "/post/([0-9]*)", UserController::class, "postLike");
Router::add("POST", "/post/([0-9]*)/comment", UserController::class, "postComment");
Router::add("DELETE", "/post/([0-9]*)/comment", UserController::class, "deleteComment");
Router::add("GET", "/upload", HomeController::class, "upload");
Router::add("POST", "/upload", HomeController::class, "postUpload");
Router::add("GET", "/search", HomeController::class, "search");

//auth
Router::add("GET", "/login", UserController::class, "login");
Router::add("POST", "/login", UserController::class, "postLogin");
Router::add("GET", "/register", UserController::class, "register");
Router::add("POST", "/register", UserController::class, "postRegister");
Router::add("GET", "/logout", UserController::class, "logout");

// User
Router::add("GET", "/user/dashboard", UserController::class, "dashboard");
Router::add("PUT", "/user/dashboard", UserController::class, "putUpdateProfile");
Router::add("PATCH", "/user/dashboard", UserController::class, "patchUpdatePassword");
Router::add("GET", "/user/dashboard/liked-posts", UserController::class, "likedPosts");
Router::add("DELETE", "/user/dashboard/liked-posts", UserController::class, "dislike");
Router::add("GET", "/user/dashboard/manage-posts", UserController::class, "managePosts");
Router::add("DELETE", "/user/dashboard/manage-posts", UserController::class, "deletePost");
Router::add("GET", "/user/dashboard/manage-posts/([0-9]*)", UserController::class, "updatePost");
Router::add("PUT", "/user/dashboard/manage-posts/([0-9]*)", UserController::class, "putUpdatePost");

// Admin
Router::add("GET", "/admin/dashboard", HomeController::class, "function");
Router::add("PUT", "/admin/dashboard", HomeController::class, "function");
Router::add("PATCH", "/admin/dashboard", HomeController::class, "function");
Router::add("GET", "/admin/dashboard/liked-posts", HomeController::class, "function");
Router::add("DELETE", "/admin/dashboard/liked-posts", HomeController::class, "function");
Router::add("GET", "/admin/dashboard/manage-posts", HomeController::class, "function");
Router::add("DELETE", "/admin/dashboard/manage-posts", HomeController::class, "function");
Router::add("GET", "/user/dashboard/manage-posts/([0-9]*)", HomeController::class, "function");
Router::add("PUT", "/admin/dashboard/manage-posts/([0-9]*)", HomeController::class, "function");
Router::add("GET", "/admin/dashboard/manage-users", HomeController::class, "function");
Router::add("GET", "/admin/dashboard/manage-users/([0-9]*)", HomeController::class, "function");
Router::add("PUT", "/admin/dashboard/manage-users/([0-9]*)", HomeController::class, "function");

Router::run();
