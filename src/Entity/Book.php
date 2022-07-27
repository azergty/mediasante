<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use function Symfony\Component\String\u;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity=Author::class, inversedBy="books")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=BookRenting::class, mappedBy="book", orphanRemoval=true)
     */
    private $bookRentings;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="books")
     */
    private $category;


    public function __construct()
    {
        $this->bookRentings = new ArrayCollection();
        $this->category = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, BookRenting>
     */
    public function getBookRentings(): Collection
    {
        return $this->bookRentings;
    }

    public function addBookRenting(BookRenting $bookRenting): self
    {
        if (!$this->bookRentings->contains($bookRenting)) {
            $this->bookRentings[] = $bookRenting;
            $bookRenting->setBook($this);
        }

        return $this;
    }

    public function removeBookRenting(BookRenting $bookRenting): self
    {
        if ($this->bookRentings->removeElement($bookRenting)) {
            // set the owning side to null (unless already changed)
            if ($bookRenting->getBook() === $this) {
                $bookRenting->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    /**
     * @return mixed
     */
    public function getIsRent()
    {
       // return $this->is_rent;
        foreach ($this->getBookRentings() as $rent){
            if($rent->getRentingEnd() === null ) return true;
        }
        return false;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
