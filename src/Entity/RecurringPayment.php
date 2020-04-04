<?php

namespace App\Entity;

use App\Entity\Traits\TimestampableTrait;
use App\Enum\RecurringPaymentPeriod;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\Constraint as CustomAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecurringPaymentRepository")
 * @ORM\HasLifecycleCallbacks()
 * @CustomAssert\HasExpenseConstraint(groups={"deleteScenario"})
 */
class RecurringPayment
{
    use TimestampableTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expense", mappedBy="recurringPayment")
     */
    private $expenses;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $period = RecurringPaymentPeriod::MONTH;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank()
     */
    private $activationTime;

    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank()
     */
    private $isAutomated = false;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paymentTag;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Expense[]
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expense $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
            $expense->setRecurringPayment($this);
        }

        return $this;
    }

    public function removeExpense(Expense $expense): self
    {
        if ($this->expenses->contains($expense)) {
            $this->expenses->removeElement($expense);
            // set the owning side to null (unless already changed)
            if ($expense->getRecurringPayment() === $this) {
                $expense->setRecurringPayment(null);
            }
        }

        return $this;
    }

    public function getIsAutomated(): ?bool
    {
        return $this->isAutomated;
    }

    public function setIsAutomated(bool $isAutomated): self
    {
        $this->isAutomated = $isAutomated;

        return $this;
    }

    public function getPeriod(): ?string
    {
        return $this->period;
    }

    public function setPeriod(string $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getActivationTime(): ?\DateTimeInterface
    {
        return $this->activationTime;
    }

    public function setActivationTime(\DateTimeInterface $activationTime): self
    {
        $this->activationTime = $activationTime;

        return $this;
    }

    public function getPaymentTag(): ?string
    {
        return $this->paymentTag;
    }

    public function setPaymentTag(?string $paymentTag): self
    {
        $this->paymentTag = $paymentTag;

        return $this;
    }
}
