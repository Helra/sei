<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Joke", mappedBy="category")
     */
    private $category_id;

    public function __construct()
    {
        $this->category_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Joke[]
     */
    public function getCategoryId(): Collection
    {
        return $this->category_id;
    }

    /**
     * @param Joke $categoryId
     * @return Category
     */

    public function addCategoryId(Joke $categoryId): self
    {
        if (!$this->category_id->contains($categoryId)) {
            $this->category_id[] = $categoryId;
            $categoryId->setCategory($this);
        }

        return $this;
    }
    /**
     * @param Joke $categoryId
     * @return Category
     */

    public function removeCategoryId(Joke $categoryId): self
    {
        if ($this->category_id->contains($categoryId)) {
            $this->category_id->removeElement($categoryId);
            // set the owning side to null (unless already changed)
            if ($categoryId->getCategory() === $this) {
                $categoryId->setCategory(null);
            }
        }

        return $this;
    }
}
