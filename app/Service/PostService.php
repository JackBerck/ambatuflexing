<?php

namespace JackBerck\Ambatuflexing\Service;

use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Domain\Post;
use JackBerck\Ambatuflexing\Domain\PostImage;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\DetailsPost;
use JackBerck\Ambatuflexing\Model\UserUploadPostRequest;
use JackBerck\Ambatuflexing\Repository\PostImageRepository;
use JackBerck\Ambatuflexing\Repository\PostRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;

class PostService
{
    private PostRepository $postRepository;
    private PostImageRepository $postImageRepository;
    private UserRepository $userRepository;
    private string $uploadDir = __DIR__ . "/../../public/images/posts/";

    public function __construct(PostRepository $postRepository, PostImageRepository $postImageRepository, UserRepository $userRepository)
    {
        $this->postRepository = $postRepository;
        $this->postImageRepository = $postImageRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws ValidationException
     */
    public function upload(UserUploadPostRequest $request): void
    {

        $this->validateUserUploadPostRequest($request);

        $user = $this->userRepository->findByField('id', $request->authorId);

        if ($user == null) {
            throw new ValidationException('User not found');
        }

        try {
            Database::beginTransaction();

            $post = new Post();
            $post->title = $request->title;
            $post->content = $request->content;
            $post->category = $request->category;
            $post->authorId = $user->id;

            $post = $this->postRepository->create($post);

            if (!empty($request->images)) {
                foreach ($request->images["tmp_name"] as $index => $tmp) {
                    $extension = pathinfo($request->images["name"][$index], PATHINFO_EXTENSION);
                    $imgNames = uniqid() . "." . $extension;

                    if (move_uploaded_file($tmp, $this->uploadDir . $imgNames)) {
                        $postImage = new PostImage();
                        $postImage->postId = $post->id;
                        $postImage->image = $imgNames;
                        $this->postImageRepository->save($postImage);
                    }
                }
            }

            Database::commitTransaction();

        } catch (ValidationException $exception) {
            Database::rollbackTransaction();
            throw new ValidationException($exception->getMessage());
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserUploadPostRequest(UserUploadPostRequest $request): void
    {
        // Validasi untuk properti title
        if (empty($request->title)) {
            throw new ValidationException("Title is required");
        }

        // Validasi untuk properti content
        if (empty($request->content)) {
            throw new ValidationException("Content is required");
        }

        // Validasi untuk properti category
        if (empty($request->category)) {
            throw new ValidationException("Category is required");
        }

        // Validasi untuk properti authorId
        if (empty($request->authorId)) {
            throw new ValidationException("Author ID is required");
        }

        // Validasi untuk gambar
        $validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

        if ($request->images == null || count($request->images) === 0) {
            throw new ValidationException("Minimum 1 image required");
        }

        foreach ($request->images["error"] as $err) {
            if ($err !== UPLOAD_ERR_OK) {
                throw new ValidationException("Invalid file");
            }
        }

        foreach ($request->images["type"] as $type) {
            if (!in_array($type, $validTypes)) {
                throw new ValidationException("Image type not allowed");
            }
        }

        foreach ($request->images["size"] as $size) {
            if ($size > 2 * 1024 * 1024) {
                throw new ValidationException("Maximum file size exceeded");
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public function details(int $postId): DetailsPost
    {
        if ($postId == "" or $postId <= 0) {
            throw new ValidationException("Error Post Id isn't Valid");
        }

        if (($post = $this->postRepository->details($postId)) == null) {
            throw new ValidationException("Error Post is not available");
        }

        return $post;
    }

}