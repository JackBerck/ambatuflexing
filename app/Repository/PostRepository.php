<?php

namespace JackBerck\Ambatuflexing\Repository;

use JackBerck\Ambatuflexing\Domain\Post;
use JackBerck\Ambatuflexing\Model\FindPost;
use JackBerck\Ambatuflexing\Model\FindPostRequest;

class PostRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create(Post $post): Post
    {
        $statement = $this->connection->prepare("INSERT INTO posts (title,content,category,user_id) VALUES (?,?,?,?)");
        $statement->execute([$post->title, $post->content, $post->category, $post->authorId]);
        $post->id = $this->connection->lastInsertId();
        return $post;
    }

    public function update(Post $post): Post
    {
        $statement = $this->connection->prepare("UPDATE posts SET title = ?, content = ? , category = ? WHERE id = ?");
        $statement->execute([$post->title, $post->content, $post->category, $post->id]);
        return $post;
    }

    public function delete(Post $post): void
    {
        $statement = $this->connection->prepare("DELETE FROM posts WHERE id = ?");
        $statement->execute([$post->id]);
    }

    public function details(int $postId): ?array
    {
        $query = "
        SELECT posts.*, post_images.image
        FROM posts
        LEFT JOIN post_images ON posts.id = post_images.post_id
        WHERE posts.id = ?";

        $stmt = $this->connection->prepare($query);
        $stmt->execute([$postId]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $post = [
            'id' => $data[0]['id'],
            'title' => $data[0]['title'],
            'content' => $data[0]['content'],
            'category' => $data[0]['category'],
            'created_at' => $data[0]['created_at'],
            'updated_at' => $data[0]['updated_at'],
            'user_id' => $data[0]['user_id'],
            'images' => []
        ];
        foreach ($data as $row) {
            if (!empty($row['image'])) {
                $post['images'][] = $row['image'];
            }
        }

        return $post;
    }

    public function find(FindPostRequest $request): array
    {
        $limit = 20;
        $offset = ($request->page - 1) * $limit;

        // Mempersiapkan query dasar untuk pengambilan data dan menghitung total
        $query = "
        SELECT 
            p.id AS post_id, 
            p.title AS post_title, 
            p.content AS post_content, 
            p.category AS post_category, 
            p.created_at AS post_created_at, 
            p.updated_at AS post_updated_at, 
            p.user_id AS post_user_id,
            (
                SELECT pi.image 
                FROM post_images pi 
                WHERE pi.post_id = p.id 
                LIMIT 1
            ) AS banner_image,
            COUNT(*) OVER() as total_count
        FROM 
            posts p
        WHERE 1=1";

        // Mempersiapkan array untuk parameter
        $params = [];

        // Pengkondisian untuk parameter pencarian
        if ($request->title) {
            $query .= " AND p.title LIKE :title";
            $params[':title'] = '%' . $request->title . '%';
        }

        if ($request->category) {
            $query .= " AND p.category = :category";
            $params[':category'] = $request->category;
        }

        if ($request->userId) {
            $query .= " AND p.user_id = :user_id";
            $params[':user_id'] = $request->userId;
        }

        // Menambahkan limitasi dan offset
        $query .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;

        $stmt = $this->connection->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $posts = [];
        $totalPosts = 0;
        foreach ($results as $row) {
            $post = new FindPost();
            $post->id = $row['post_id'];
            $post->title = $row['post_title'];
            $post->content = $row['post_content'];
            $post->category = $row['post_category'];
            $post->createdAt = $row['post_created_at'];
            $post->updatedAt = $row['post_updated_at'];
            $post->authorId = $row['post_user_id'];
            $post->banner = $row['banner_image'];
            $posts[] = (array)$post;

            // Menentukan total jumlah postingan
            if ($totalPosts == 0) {
                $totalPosts = $row['total_count'];
            }
        }

        return [
            'posts' => $posts,
            'total' => $totalPosts
        ];
    }

}