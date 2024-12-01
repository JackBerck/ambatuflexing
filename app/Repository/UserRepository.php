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
        $statement = $this->connection->prepare("INSERT INTO users(full_name,email, password) VALUES (?, ?, ?)");
        $statement->execute([
            $user->fullName,
            $user->email,
            $user->password
        ]);
        return $user;
    }

    public function update(User $user): User
    {
        $statement = $this->connection->prepare("UPDATE users SET full_name = ?, password = ?, photo = ? WHERE id = ?");
        $statement->execute([
            $user->fullName,
            $user->password,
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

        $statement = $this->connection->prepare("SELECT id, full_name,email, password, photo FROM users WHERE $field = ?");
        $statement->execute([$value]);

        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->id = $row['id'];
                $user->fullName = $row['full_name'];
                $user->email = $row['email'];
                $user->password = $row['password'];
                $user->photo = $row['photo'];
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
