<?php

namespace App\DAO;

use App\Model\User;

class UserManager extends DAO
{
    public function __construct()
    {
        parent::__construct('user');
    }

    public function login(string $username, string $password): bool
    {
//        dd("SELECT * FROM `user` WHERE name = '$username' AND password = '$password'");
        return (bool) $this->createQuery("SELECT * FROM `user` WHERE name = '$username' AND password = '$password'")->rowCount();
    }

    public function create(User $user): int
    {
        return $this->createQuery(
            'INSERT INTO user (name, role, password) VALUES (?, ?, ?)',
            $this->buildValues($user)
        );
    }

    public function update(User $user): bool
    {
        $result = $this->createQuery(
            'UPDATE user SET name = ?, role = ?, password = ? WHERE id = ?',
            array_merge($this->buildValues($user), [$user->getId()])
        );

        return 1 <= $result->rowCount();
    }

    public function buildValues(object $object): array
    {
        return [
            $object->getName(),
            $object->getRole(),
            $object->getPassword(),
        ];
    }

    public function buildObject(object $object): User
    {
        return (new User())
            ->setId($object->id)
            ->setName($object->name)
            ->setRole($object->role)
            ->setPassword($object->password)
        ;
    }
}
