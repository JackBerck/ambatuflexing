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
        $statement = $this->connection->prepare("UPDATE users SET username = ?, password = ?,position = ?,bio = ?, photo = ?  WHERE id = ?");
        $statement->execute([
            $user->username,
            $user->password,
            $user->position,
            $user->bio,
            $user->photo,
            $user->id
        ]);
        return $user;
    }

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

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE from users");
    }
}
