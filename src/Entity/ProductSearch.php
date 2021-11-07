<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class ProductSearch
{
    /**
     * @var string|null
     */
    private $name;

    /**
     * @var Category[]
     */
    private $categories = [];

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @param Category[] $categories
     */
    public function setCategories(array $categories): void
    {
        $this->categories = $categories;
    }


}
