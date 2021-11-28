<?php

namespace App\DAO;

use App\Model\Testimonial;

class TestimonialManager extends DAO
{
    public function __construct()
    {
        parent::__construct('testimonial');
    }

    public function create(Testimonial $testimonial): int
    {
        return $this->createQuery(
            'INSERT INTO testimonial (name, role, message) VALUES (?, ?, ?)',
            $this->buildValues($testimonial)
        );
    }

    public function update(Testimonial $testimonial): bool
    {
        $result = $this->createQuery(
            'UPDATE testimonial SET name = ?, role = ?, message = ? WHERE id = ?',
            array_merge($this->buildValues($testimonial), [$testimonial->getId()])
        );

        return 1 <= $result->rowCount();
    }

    public function buildValues(object $object): array
    {
        return [
            $object->getName(),
            $object->getRole(),
            $object->getMessage(),
        ];
    }

    public function buildObject(object $object): Testimonial
    {
        return (new Testimonial())
            ->setId($object->id)
            ->setName($object->name)
            ->setRole($object->role)
            ->setMessage($object->message)
        ;
    }
}
