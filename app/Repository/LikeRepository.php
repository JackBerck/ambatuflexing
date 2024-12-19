<?php

namespace JackBerck\Ambatuflexing\Repository;

use JackBerck\Ambatuflexing\Domain\Like;
use JackBerck\Ambatuflexing\Model\FindPost;

class LikeRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function like(Like $like): Like
    {
        $stmt = $this->connection->prepare("INSERT INTO likes (user_id,post_id) VALUES (?,?)");
        $stmt->execute([$like->userId, $like->postId]);
        return $like;
    }

    public function dislike(Like $like): void
    {
        $stmt = $this->connection->prepare("DELETE FROM likes WHERE user_id=? AND post_id=?");
        $stmt->execute([$like->userId, $like->postId]);
    }

    public function detail(int $userId, int $postId): ?Like
    {
        $stmt = $this->connection->prepare("SELECT user_id,post_id,created_at FROM likes WHERE user_id=? AND post_id=?");
        $stmt->execute([$userId, $postId]);
        $data = $stmt->fetch();
        try {
            if ($data) {
                $like = new Like();
                $like->postId = $data["post_id"];
                $like->userId = $data["user_id"];
                $like->createdAt = $data["created_at"];
                return $like;
            } else {
                return null;
            }
        } finally {
            $stmt->closeCursor();
        }
    }

    public function getLikesCount(int $postId): int
    {
        $stmt = $this->connection->prepare("SELECT COUNT(*) as total FROM likes WHERE post_id=?");
        $stmt->execute([$postId]);
        $data = $stmt->fetch();
        $stmt->closeCursor();
        return isset($data['total']) ? (int)$data['total'] : 0;
    }

    public function likedPosts(int $userId, int $page = 1, $limit = 50): array
    {
        $offset = ($page - 1) * $limit;

        // Query utama untuk mengambil liked posts
        $sql = "
    SELECT 
        p.id AS post_id, 
        p.title AS post_title, 
        p.content AS post_content, 
        p.category AS post_category, 
        p.created_at AS post_created_at, 
        p.updated_at AS post_updated_at,
        u.id AS authorId,
        u.username AS author, 
        u.photo AS photo,
        u.position AS position,
        pi.image AS banner_image,
        (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS total_likes,
        (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS total_comments
    FROM 
        likes l
    JOIN 
        posts p ON l.post_id = p.id
    JOIN 
        users u ON p.user_id = u.id
    LEFT JOIN 
        post_images pi ON pi.post_id = p.id
    WHERE 
        l.user_id = ?
    LIMIT 
        ? OFFSET ?
    ";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $stmt->bindValue(2, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(3, $offset, \PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $likedPosts = [];
        foreach ($data as $row) {
            $liked = new FindPost();
            $liked->id = $row['post_id'];
            $liked->title = $row['post_title'];
            $liked->content = $row['post_content'];
            $liked->category = $row['post_category'];
            $liked->createdAt = $row['post_created_at'];
            $liked->updatedAt = $row['post_updated_at'];
            $liked->authorId = $row['authorId'];
            $liked->banner = $row['banner_image'];
            $liked->author = $row['author'];
            $liked->authorPosition = $row['position'];
            $liked->authorPhoto = $row['photo'];
            $liked->commentCount = $row['total_comments'];
            $liked->likeCount = $row['total_likes'];

            $likedPosts[] = $liked;
        }

        // Query terpisah untuk mendapatkan total liked posts
        $countQuery = "
    SELECT 
        COUNT(*) as total_count
    FROM 
        likes l
    JOIN 
        posts p ON l.post_id = p.id
    WHERE 
        l.user_id = ?
    ";

        $countStmt = $this->connection->prepare($countQuery);
        $countStmt->bindValue(1, $userId, \PDO::PARAM_INT);
        $countStmt->execute();
        $totalLikedPosts = $countStmt->fetchColumn();

        return [
            'total' => (int)$totalLikedPosts,
            'likedPost' => $likedPosts,
        ];
    }


}