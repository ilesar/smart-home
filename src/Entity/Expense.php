<?php

namespace App\Entity;

use App\Entity\Traits\ResolvableTrait;
use App\Entity\Traits\TimestampableTrait;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpenseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Expense
{
    use TimestampableTrait;

    use ResolvableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="due_date", type="datetime_immutable")
     */
    private $dueDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RecurringPayment", inversedBy="expenses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recurringPayment;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDueDate(): ?DateTimeImmutable
    {
        return $this->dueDate;
    }

    public function setDueDate(DateTimeImmutable $dueDate): self
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function getRecurringPayment(): ?RecurringPayment
    {
        return $this->recurringPayment;
    }

    public function setRecurringPayment(?RecurringPayment $recurringPayment): self
    {
        $this->recurringPayment = $recurringPayment;

        return $this;
    }
}
