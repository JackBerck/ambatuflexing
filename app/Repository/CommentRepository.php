<?php

namespace JackBerck\Ambatuflexing\Repository;

use JackBerck\Ambatuflexing\Domain\Comment;

class CommentRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function comment(Comment $comment): Comment
    {
        $stmt = $this->connection->prepare("INSERT INTO comments (user_id,post_id,comment) VALUES (?,?,?)");
        $stmt->execute([$comment->userId, $comment->postId, $comment->comment]);
        $comment->id = $this->connection->lastInsertId();
        return $comment;
    }

    public function remove(int $commentId): void
    {
        $stmt = $this->connection->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);
    }

    public function find(int $commentId): ?Comment
    {
        $stmt = $this->connection->prepare("SELECT id,comment,user_id,post_id,created_at FROM comments WHERE id = ?");
        $stmt->execute([$commentId]);

        try {
            if ($row = $stmt->fetch()) {
                $comment = new Comment();
                $comment->id = $row['id'];
                $comment->comment = $row['comment'];
                $comment->userId = $row['user_id'];
                $comment->postId = $row['post_id'];
                $comment->createdAt = $row['created_at'];
                return $comment;
            } else {
                return null;
            }
        } finally {
            $stmt->closeCursor();
        }
    }

    public function getComment(int $postId): array
    {
        $stmt = $this->connection->prepare("
            SELECT 
                id,
                users.photo,
                users.username,
                users.position,
                comment,
                created_at,
            FROM
                comments
            JOIN 
                users ON comments.user_id = users.id
            WHERE post_id = ?
            ");
        $stmt->execute([$postId]);
        $data = $stmt->fetchAll();
        $comments = [];
        foreach ($data as $comment) {
            $result = new Comment();
            $result->id = $comment["id"];
            $result->userId = $comment["user_id"];
            $result->postId = $comment["post_id"];
            $result->createdAt = $comment["created_at"];
            $result->comment = $comment["comment"];
            $comments[] = (array)$result;
        }
        return $comments;
    }
}