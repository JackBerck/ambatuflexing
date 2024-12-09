<?php

namespace JackBerck\Ambatuflexing\Service;

use JackBerck\Ambatuflexing\Config\Database;
use JackBerck\Ambatuflexing\Domain\Comment;
use JackBerck\Ambatuflexing\Domain\Like;
use JackBerck\Ambatuflexing\Domain\Post;
use JackBerck\Ambatuflexing\Domain\PostImage;
use JackBerck\Ambatuflexing\Exception\ValidationException;
use JackBerck\Ambatuflexing\Model\DetailsPost;
use JackBerck\Ambatuflexing\Model\FindPostRequest;
use JackBerck\Ambatuflexing\Model\FindPostResponse;
use JackBerck\Ambatuflexing\Model\UserCommentPostRequest;
use JackBerck\Ambatuflexing\Model\UserDeletePostRequest;
use JackBerck\Ambatuflexing\Model\UserDislikePostRequest;
use JackBerck\Ambatuflexing\Model\UserGetLikedPostRequest;
use JackBerck\Ambatuflexing\Model\UserGetLikedPostResponse;
use JackBerck\Ambatuflexing\Model\UserLikePostRequest;
use JackBerck\Ambatuflexing\Model\UserRemoveCommentPostRequest;
use JackBerck\Ambatuflexing\Model\UserUpdatePostRequest;
use JackBerck\Ambatuflexing\Model\UserUploadPostRequest;
use JackBerck\Ambatuflexing\Repository\CommentRepository;
use JackBerck\Ambatuflexing\Repository\LikeRepository;
use JackBerck\Ambatuflexing\Repository\PostImageRepository;
use JackBerck\Ambatuflexing\Repository\PostRepository;
use JackBerck\Ambatuflexing\Repository\UserRepository;

class PostService
{
    private PostRepository $postRepository;
    private PostImageRepository $postImageRepository;
    private UserRepository $userRepository;
    private LikeRepository $likeRepository;
    private CommentRepository $commentRepository;
    private string $uploadDir = __DIR__ . "/../../public/images/posts/";

    public function __construct(PostRepository $postRepository, PostImageRepository $postImageRepository, UserRepository $userRepository, LikeRepository $likeRepository, CommentRepository $commentRepository)
    {
        $this->postRepository = $postRepository;
        $this->postImageRepository = $postImageRepository;
        $this->userRepository = $userRepository;
        $this->likeRepository = $likeRepository;
        $this->commentRepository = $commentRepository;
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

    public function search(FindPostRequest $request): FindPostResponse
    {
        return $this->postRepository->find($request);
    }

    /**
     * @throws ValidationException
     */
    public function remove(UserDeletePostRequest $request): void
    {
        $this->validateUserDeletePostRequest($request);

        try {
            Database::beginTransaction();
            $post = $this->postRepository->details($request->postId);
            if ($post === null) throw new ValidationException("Error Post is not available");

            $user = $this->userRepository->findByField('id', $request->userId);
            if ($user === null) {
                throw new ValidationException("user not found");
            }

            if ($user->isAdmin != 'admin' && $user->id != $post->post->authorId) {
                throw new ValidationException("Cannot delete post");
            }

            foreach ($post->images as $image) {
                if (file_exists($this->uploadDir . $image->image)) {
                    unlink($this->uploadDir . $image->image);
                }
            }

            $this->postRepository->delete($request->postId);
            Database::commitTransaction();
        } catch (ValidationException $exception) {
            Database::rollbackTransaction();
            throw new ValidationException($exception->getMessage());
        }
    }

    private function validateUserDeletePostRequest(UserDeletePostRequest $request): void
    {
        if ($request->postId == "" or $request->postId <= 0 or empty($request->postId)) throw new ValidationException("Error Post Id isn't Valid");
        if ($request->userId == "" or $request->userId <= 0 or empty($request->userId)) throw new ValidationException("User Id is required");
    }

    public function likedPost(UserGetLikedPostRequest $request): UserGetLikedPostResponse
    {
        $data = $this->likeRepository->likedPosts($request->userId, $request->page, $request->limit);
        $result = new UserGetLikedPostResponse();
        $result->likedPost = $data['likedPost'];
        $result->totalPost = $data['total'];
        return $result;
    }

    /**
     * @throws \Exception
     */
    public function like(UserLikePostRequest $request): void
    {
        $this->ValidateLikeAndDislike($request);
        try {
            Database::beginTransaction();

            $likePost = new Like();
            $likePost->postId = $request->postId;
            $likePost->userId = $request->userId;
            $this->likeRepository->like($likePost);
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws \Exception
     */
    public function dislike(UserDislikePostRequest $request): void
    {
        $this->ValidateLikeAndDislike($request);
        try {
            Database::beginTransaction();

            $dislikePost = new Like();
            $dislikePost->postId = $request->postId;
            $dislikePost->userId = $request->userId;
            $this->likeRepository->dislike($dislikePost);
            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function ValidateLikeAndDislike(UserLikePostRequest $request): void
    {
        if ($request->postId == "" or $request->postId <= 0 or empty($request->postId)) throw new ValidationException("Error Post Id isn't Valid");
        if ($request->userId == "" or $request->userId <= 0 or empty($request->userId)) throw new ValidationException("User Id is required");
        $user = $this->userRepository->findByField('id', $request->postId);
        if ($user == null) throw new ValidationException("User not found");
        $post = $this->postRepository->details($request->postId);
        if ($post == null) throw new ValidationException('Post not found');
    }

    /**
     * @throws ValidationException
     */
    public function update(UserUpdatePostRequest $request): void
    {

        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField('id', $request->userId);
            if ($user == null) throw new ValidationException('User not found');

            $data = $this->postRepository->details($request->postId);
            if ($data == null) throw new ValidationException('Post not found');

            if ($user->isAdmin != 'admin' && $user->id != $data->post->authorId) {
                throw new ValidationException("Cannot update post");
            }

            $data->post->title = $request->title;
            $data->post->content = $request->content;
            $data->post->category = $request->category;

            $this->postRepository->update($data->post);

            Database::commitTransaction();
        } catch (ValidationException $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    public function comment(UserCommentPostRequest $request): void
    {
        $this->validateUserCommentPostRequest($request);
        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField('id', $request->userId);
            if ($user == null) throw new ValidationException('User not found');
            $post = $this->postRepository->details($request->postId);
            if ($post == null) throw new ValidationException('Post not found');

            $comment = new Comment();
            $comment->postId = $request->postId;
            $comment->userId = $request->userId;
            $comment->comment = $request->comment;

            $this->commentRepository->comment($comment);

            Database::commitTransaction();
        } catch (ValidationException $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    private function validateUserCommentPostRequest(UserCommentPostRequest $request): void
    {
        if ($request->postId <= 0 or empty($request->postId)) throw new ValidationException("Error Post Id isn't Valid");
        if ($request->userId <= 0 or empty($request->userId)) throw new ValidationException("User Id is required");
        if ($request->comment == "" or empty($request->comment)) throw new ValidationException("Comment cannot be empty");
    }

    /**
     * @throws ValidationException
     */
    public function removeComment(UserRemoveCommentPostRequest $request): void
    {
        $this->validateUserRemoveCommentPostRequest($request);
        try {
            Database::beginTransaction();

            $user = $this->userRepository->findByField('id', $request->userId);
            if ($user == null) throw new ValidationException('User not found');
            $post = $this->postRepository->details($request->postId);
            if ($post == null) throw new ValidationException('Post not found');
            $comment = $this->commentRepository->getComment($request->commentId);
            if ($comment == null) throw new ValidationException("Comment not found");
            if ($user->isAdmin !== 'admin' and $user->id != $request->userId) throw new ValidationException("User cannot deleted this comment");

            $this->commentRepository->remove($request->commentId);

            Database::commitTransaction();
        } catch (ValidationException $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }

    /**
     * @throws ValidationException
     */
    public function validateUserRemoveCommentPostRequest(UserRemoveCommentPostRequest $request): void
    {
        if ($request->commentId <= 0 or empty($request->commentId)) throw new ValidationException("Comment id isn't valid");
        if ($request->postId <= 0 or empty($request->postId)) throw new ValidationException("Error Post Id isn't Valid");
        if ($request->userId <= 0 or empty($request->userId)) throw new ValidationException("User Id is required");
    }

}