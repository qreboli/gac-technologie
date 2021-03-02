<?php

namespace App\Entity;

use App\Repository\CommunicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommunicationRepository::class)
 */
class Communication
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Type")
     */
    private $type;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $real_duration;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    private $billed_duration;

    /**
     * @ORM\ManyToOne(targetEntity="Bill", inversedBy="communications")
     */
    private $bill;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(Type $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getRealDuration(): ?\DateTimeInterface
    {
        return $this->real_duration;
    }

    public function setRealDuration(?\DateTimeInterface $real_duration): self
    {
        $this->real_duration = $real_duration;

        return $this;
    }

    public function getBilledDuration(): ?\DateTimeInterface
    {
        return $this->billed_duration;
    }

    public function setBilledDuration(?\DateTimeInterface $billed_duration): self
    {
        $this->billed_duration = $billed_duration;

        return $this;
    }

    public function getBill(): Bill
    {
        return $this->bill;
    }

    public function setBill(Bill $bill): self
    {
        $this->bill = $bill;

        return $this;
    }
}
