<?php

namespace App\Entity;

use App\Repository\BookRentingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BookRentingRepository::class)
 */
class BookRenting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookRentings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="bookRentings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    /**
     * @ORM\Column(type="datetime")
     */
    private $renting_start;

    /**
     * @ORM\Column(type="datetime",  nullable=true)
     */
    private $renting_end;

    /**
     * @ORM\Column(type="datetime")
     */
    private $limit_date;

    const LIMIT_RENTED_DAYS=30;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }

    public function getRentingStart(): ?\DateTimeInterface
    {
        return $this->renting_start;
    }

    public function setRentingStart(\DateTimeInterface $renting_start): self
    {
        $this->renting_start = $renting_start;

        $date_end = date('Y-m-d H:i:s',strtotime($this->renting_start->format('Y-m-d H:i:s'))+(self::LIMIT_RENTED_DAYS*24*3600));
        $date_end =new \DateTimeImmutable($date_end);
        $this->setLimitDate($date_end);
        return $this;
    }

    public function getRentingEnd(): ?\DateTimeInterface
    {
        return $this->renting_end;
    }

     public function setRentingEnd(\DateTimeInterface $renting_end): self
    {
        $this->renting_end = $renting_end;

        return $this;
    }

    public function getLimitDate(): ?\DateTimeInterface
    {
        return $this->limit_date;
    }

    private function setLimitDate(\DateTimeInterface $limit_date): self
    {
        $this->limit_date = $limit_date;

        return $this;
    }
}
