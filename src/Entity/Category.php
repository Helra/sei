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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Joke", mappedBy="category", orphanRemoval=true)
     */
    private $jokes;

    public function __construct()
    {
        $this->jokes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Joke[]
     */
    public function getJokes(): Collection
    {
        return $this->jokes;
    }

    public function addJoke(Joke $joke): self
    {
        if (!$this->jokes->contains($joke)) {
            $this->jokes[] = $joke;
            $joke->setCategory($this);
        }

        return $this;
    }

    public function removeJoke(Joke $joke): self
    {
        if ($this->jokes->contains($joke)) {
            $this->jokes->removeElement($joke);
            // set the owning side to null (unless already changed)
            if ($joke->getCategory() === $this) {
                $joke->setCategory(null);
            }
        }

        return $this;
    }
}
