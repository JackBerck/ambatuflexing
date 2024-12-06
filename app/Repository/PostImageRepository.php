<?php

namespace JackBerck\Ambatuflexing\Repository;

use JackBerck\Ambatuflexing\Domain\PostImage;

class PostImageRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(PostImage $postImage): PostImage
    {
        $stmt = $this->connection->prepare("INSERT INTO post_images (post_id,image) VALUES (?,?)");
        $stmt->execute([$postImage->postId, $postImage->image]);
        $postImage->id = $this->connection->lastInsertId();
        return $postImage;
    }

    public function delete(int $postImageId): void
    {
        $stmt = $this->connection->prepare("DELETE FROM post_images WHERE id = ?");
        $stmt->execute([$postImageId]);
    }
}