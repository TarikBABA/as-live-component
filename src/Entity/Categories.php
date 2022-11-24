<?php

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoriesRepository::class)]
class Categories
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'categories', targetEntity: Species::class, orphanRemoval: true)]
    private Collection $type;

    public function __construct()
    {
        $this->type = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Species>
     */
    public function getType(): Collection
    {
        return $this->type;
    }

    public function addType(Species $type): self
    {
        if (!$this->type->contains($type)) {
            $this->type->add($type);
            $type->setCategories($this);
        }

        return $this;
    }

    public function removeType(Species $type): self
    {
        if ($this->type->removeElement($type)) {
            // set the owning side to null (unless already changed)
            if ($type->getCategories() === $this) {
                $type->setCategories(null);
            }
        }

        return $this;
    }
}
