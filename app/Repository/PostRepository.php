<?php

namespace JackBerck\Ambatuflexing\Repository;

use JackBerck\Ambatuflexing\Domain\Post;
use JackBerck\Ambatuflexing\Model\DetailsPost;
use JackBerck\Ambatuflexing\Model\FindPost;
use JackBerck\Ambatuflexing\Model\FindPostRequest;
use JackBerck\Ambatuflexing\Model\FindPostResponse;

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

    public function delete(int $postId): void
    {
        $statement = $this->connection->prepare("DELETE FROM posts WHERE id = ?");
        $statement->execute([$postId]);
    }


    public function details(int $postId): ?DetailsPost
    {
        $query = "
        SELECT 
            posts.*, 
            users.username, 
            users.photo, 
            users.position,
            COALESCE(GROUP_CONCAT(post_images.image ORDER BY post_images.id ASC), '') AS images,
            (SELECT COUNT(*) FROM likes WHERE post_id = posts.id) AS total_likes,
            (SELECT COUNT(*) FROM comments WHERE post_id = posts.id) AS total_comments
        FROM 
            posts
        LEFT JOIN 
            post_images ON posts.id = post_images.post_id
        LEFT JOIN 
            users ON posts.user_id = users.id
        WHERE 
            posts.id = ?
        GROUP BY 
            posts.id, users.username, users.photo, users.position";

        $stmt = $this->connection->prepare($query);
        $stmt->execute([$postId]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Jika tidak ada data yang ditemukan, return null
        if (empty($data)) {
            return null;
        }

        // Membuat objek Post
        $post = new Post();
        $post->id = $data['id'];
        $post->title = $data['title'];
        $post->content = $data['content'];
        $post->category = $data['category'];
        $post->createdAt = $data['created_at'];
        $post->updatedAt = $data['updated_at'];
        $post->authorId = $data['user_id'];

        // Memproses gambar-gambar yang terkait
        $images = explode(',', $data['images']);

        // Membuat objek DetailsPost
        $result = new DetailsPost();
        $result->post = $post;
        $result->images = $images;
        $result->author = $data['username'];
        $result->authorPhoto = $data['photo'];
        $result->authorPosition = $data['position'];
        $result->likeCount = (int)$data['total_likes'];
        $result->commentCount = (int)$data['total_comments'];

        return $result;
    }

    public function find(FindPostRequest $request): FindPostResponse
    {
        $offset = ($request->page - 1) * $request->limit;

        // Mempersiapkan query dasar untuk pengambilan data
        $query = "
    SELECT 
        p.id AS post_id, 
        p.title AS post_title, 
        p.content AS post_content, 
        p.category AS post_category, 
        p.created_at AS post_created_at, 
        p.updated_at AS post_updated_at, 
        p.user_id AS post_user_id,
        u.username AS post_author,
        u.position AS post_author_position,
        u.photo AS post_author_photo,
        (SELECT pi.image FROM post_images pi WHERE pi.post_id = p.id LIMIT 1) AS banner_image,
        (SELECT COUNT(*) FROM likes WHERE post_id = p.id) AS total_likes,
        (SELECT COUNT(*) FROM comments WHERE post_id = p.id) AS total_comments
    FROM 
        posts p
    JOIN 
        users u ON p.user_id = u.id 
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
        $params[':limit'] = $request->limit;
        $params[':offset'] = $offset;

        $stmt = $this->connection->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Query terpisah untuk mendapatkan total count
        $countQuery = "
    SELECT 
        COUNT(*) as total_count
    FROM 
        posts p
    JOIN 
        users u ON p.user_id = u.id
    WHERE 1=1";

        // Gunakan kembali kondisi yang sama untuk konsistensi
        if ($request->title) {
            $countQuery .= " AND p.title LIKE :title";
        }

        if ($request->category) {
            $countQuery .= " AND p.category = :category";
        }

        if ($request->userId) {
            $countQuery .= " AND p.user_id = :user_id";
        }

        $countStmt = $this->connection->prepare($countQuery);
        foreach ($params as $key => &$value) {
            $countStmt->bindParam($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $countStmt->execute();
        $totalPosts = $countStmt->fetchColumn();

        $posts = [];
        foreach ($results as $row) {
            $post = new FindPost();
            $post->id = $row['post_id'];
            $post->title = $row['post_title'];
            $post->content = $row['post_content'];
            $post->category = $row['post_category'];
            $post->createdAt = $row['post_created_at'];
            $post->updatedAt = $row['post_updated_at'];
            $post->authorId = $row['post_user_id'];
            $post->author = $row['post_author'];
            $post->authorPosition = $row['post_author_position'];
            $post->authorPhoto = $row['post_author_photo'];
            $post->banner = $row['banner_image'];
            $post->likeCount = (int)$row['total_likes'];
            $post->commentCount = (int)$row['total_comments'];
            $posts[] = (array)$post;
        }

        $result = new FindPostResponse();
        $result->posts = $posts;
        $result->totalPost = (int)$totalPosts;
        return $result;
    }

}