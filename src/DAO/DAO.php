<?php

namespace App\DAO;

abstract class DAO
{
    private \PDO $database;
    private string $tableName;

    public function __construct(string $tableName)
    {
        $this->database = new \PDO(
            $_ENV['DSN'],
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            ]
        );
        $this->tableName = $tableName;
    }

    public function createQuery(string $sql, array $params = [])
    {
        if (!$params) {
            return $this->database->query($sql);
        }

        $result = $this->database->prepare($sql);
        $result->execute($params);

        if (preg_match('/^INSERT INTO/i', $sql)) {
            return $this->database->lastInsertId();
        }

        return $result;
    }

    public function findAll(): array
    {
        $result = $this->createQuery("SELECT * FROM {$this->tableName}");

        $objects = [];
        foreach ($result->fetchAll() as $object) {
            $objects[] = $this->buildObject($object);
        }

        return $objects;
    }

    public function findOneBy(string $attribute, $value): ?object
    {
        $result = $this->createQuery("SELECT * FROM {$this->tableName} WHERE {$attribute} = ?", [$value]);

        if (false === $object = $result->fetchObject()) {
            return null;
        }

        return $this->buildObject($object);
    }

    public function findBy(string $attribute, $value): array
    {
        $result = $this->createQuery("SELECT * FROM {$this->tableName} WHERE {$attribute} = ?", [$value]);

        $objects = [];
        foreach ($result->fetchAll() as $object) {
            $objects[] = $this->buildObject($object);
        }

        return $objects;
    }

    public function delete(object $object): bool
    {
        $result = $this->createQuery("DELETE FROM {$this->tableName} WHERE id = ?", [$object->getId()]);

        return 1 <= $result->rowCount();
    }

    abstract public function buildObject(object $object): object;

    abstract public function buildValues(object $object): array;
}
