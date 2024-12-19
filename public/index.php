<?php

require_once __DIR__ . '/../vendor/autoload.php';

use JackBerck\Ambatuflexing\App\Router;
use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Controller\HomeController;
use JackBerck\Ambatuflexing\Controller\UserController;
use JackBerck\Ambatuflexing\Middleware\MustNotLoginMiddleware;
use JackBerck\Ambatuflexing\Middleware\MustLoginMiddleware;
use JackBerck\Ambatuflexing\Middleware\MustUserMiddleware;
use JackBerck\Ambatuflexing\Middleware\MustAdminMiddleware;
use JackBerck\Ambatuflexing\Controller\AdminController;

Database::getConnection('prod');

//set timezone
date_default_timezone_set("Asia/jakarta");

//set session for flasher message
if (!session_id()) session_start();

// Home
Router::add("GET", "/", HomeController::class, "index");
Router::add("GET", "/about", HomeController::class, "about");
Router::add("GET", "/post/([0-9]*)", HomeController::class, "detail");
Router::add("POST", "/post/([0-9]*)", UserController::class, "postLike", [MustLoginMiddleware::class]);
Router::add("POST", "/post/([0-9]*)/comment", UserController::class, "postComment", [MustLoginMiddleware::class]);
Router::add("POST", "/post/([0-9]*)/comment/delete", UserController::class, "deleteComment", [MustLoginMiddleware::class]);
Router::add("GET", "/upload", HomeController::class, "upload", [MustLoginMiddleware::class]);
Router::add("POST", "/upload", HomeController::class, "postUpload", [MustLoginMiddleware::class]);
Router::add("GET", "/search", HomeController::class, "search");
Router::add("GET", "/profile/([0-9]*)", UserController::class, "detailUser");

//auth
Router::add("GET", "/login", UserController::class, "login", [MustNotLoginMiddleware::class]);
Router::add("POST", "/login", UserController::class, "postLogin", [MustNotLoginMiddleware::class]);
Router::add("GET", "/register", UserController::class, "register", [MustNotLoginMiddleware::class]);
Router::add("POST", "/register", UserController::class, "postRegister", [MustNotLoginMiddleware::class]);
Router::add("GET", "/logout", UserController::class, "logout", [MustLoginMiddleware::class]);

// User
Router::add("GET", "/user/dashboard", UserController::class, "dashboard", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("POST", "/user/dashboard", UserController::class, "postUpdateProfile", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("POST", "/user/dashboard/password", UserController::class, "patchUpdatePassword", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("GET", "/user/liked-posts", UserController::class, "likedPosts", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("POST", "/user/liked-posts", UserController::class, "dislike", [MustLoginMiddleware::class]);
Router::add("GET", "/user/manage-posts", UserController::class, "managePosts", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("POST", "/user/manage-posts", UserController::class, "deletePost", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("GET", "/user/manage-posts/([0-9]*)", UserController::class, "updatePost", [MustLoginMiddleware::class, MustUserMiddleware::class]);
Router::add("POST", "/user/manage-posts/([0-9]*)", UserController::class, "putUpdatePost", [MustLoginMiddleware::class, MustUserMiddleware::class]);

// Admin
Router::add("GET", "/admin/dashboard", AdminController::class, "dashboard", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/dashboard", UserController::class, "postUpdateProfile", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/dashboard/password", UserController::class, "patchUpdatePassword", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/liked-posts", AdminController::class, "likedPosts", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/liked-posts", UserController::class, "dislike", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/manage-posts", AdminController::class, "managePosts", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/manage-posts", UserController::class, "deletePost", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/manage-posts/([0-9]*)", AdminController::class, "updatePost", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/manage-posts/([0-9]*)", UserController::class, "putUpdatePost", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/manage-users", AdminController::class, "manageUsers", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("GET", "/admin/manage-users/([0-9]*)", AdminController::class, "updateUser", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/manage-users/([0-9]*)", AdminController::class, "putUpdateEmailUser", [MustLoginMiddleware::class, MustAdminMiddleware::class]);
Router::add("POST", "/admin/manage-users/([0-9]*)/password", AdminController::class, "patchUpdatePassword", [MustLoginMiddleware::class, MustAdminMiddleware::class]);

// error
Router::add("GET", "/error", HomeController::class, "error");

Router::run();
