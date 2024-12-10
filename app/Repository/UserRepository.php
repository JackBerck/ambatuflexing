<?php

namespace JackBerck\Ambatuflexing\Repository;

use JackBerck\Ambatuflexing\Domain\User;
use JackBerck\Ambatuflexing\Exception\ValidationException;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users(username,email, password) VALUES (?, ?, ?)");
        $statement->execute([
            $user->username,
            $user->email,
            $user->password
        ]);
        $user->id = $this->connection->lastInsertId();
        return $user;
    }

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET email = ?, username = ?, password = ?,position = ?,bio = ?, photo = ?  WHERE id = ?");
        $statement->execute([
            $user->email,
            $user->username,
            $user->password,
            $user->position,
            $user->bio,
            $user->photo,
            $user->id
        ]);
        return $user;
    }

    /**
     * @throws ValidationException
     */
    public function findByField(string $field, $value): ?User
    {

        if (!in_array($field, ['id', 'email'])) {
            throw new ValidationException('field must be one of "id", "email"');
        }

        $statement = $this->connection->prepare("SELECT id, email,username, password,position, bio ,photo,is_admin,created_at FROM users WHERE $field = ?");
        $statement->execute([$value]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->email = $row['email'];
                $user->username = $row['username'];
                $user->password = $row['password'];
                $user->position = $row['position'];
                $user->bio = $row['bio'];
                $user->photo = $row['photo'];
                $user->isAdmin = $row['is_admin'];
                $user->createdAt = $row['created_at'];

                return $user;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function search(string $email, string $username, string $position, int $page = 1, int $limit = 50): array
    {
        $query = "
        SELECT 
            id,
            username,
            email,
            password,
            position,
            bio,
            photo,
            is_admin,
            created_at
        FROM
            users
        WHERE 
            1=1
        ";

        $params = [];

        if ($email) {
            $query .= " AND email LIKE :email";
            $params[':title'] = '%' . $email . '%';
        }

        if ($username) {
            $query .= " AND username LIKE :username";
            $params[':username'] = '%' . $username . '%';
        }

        if ($position) {
            $query .= " AND position LIKE :position";
            $params[':position'] = '%' . $position . '%';
        }

        $query .= " LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = ($page - 1) * $limit;

        $stmt = $this->connection->prepare($query);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $countQuery = "
        SELECT 
            COUNT(*) as total_count
        FROM 
            users
        WHERE 1=1";

        // Gunakan kembali kondisi yang sama untuk konsistensi
        if ($email) {
            $countQuery .= " AND email LIKE :email";
        }
        if ($username) {
            $countQuery .= " AND username LIKE :username";
        }
        if ($position) {
            $countQuery .= " AND position LIKE :position";
        }

        $countStmt = $this->connection->prepare($countQuery);
        foreach ($params as $key => &$value) {
            $countStmt->bindParam($key, $value, is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR);
        }
        $countStmt->execute();
        $totalUsers = $countStmt->fetchColumn();

        $users = [];
        foreach ($results as $row) {
            $user = new User();
            $user->id = $row['id'];
            $user->email = $row['email'];
            $user->username = $row['username'];
            $user->password = $row['password'];
            $user->position = $row['position'];
            $user->bio = $row['bio'];
            $user->photo = $row['photo'];
            $user->isAdmin = $row['is_admin'];
            $user->createdAt = $row['created_at'];
            $users[] = (array)$user;
        }

        return [
            'total' => (int)$totalUsers,
            'users' => $users,
        ];
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE from users");
    }
}
