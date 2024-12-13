<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "date")]
    #[Assert\NotBlank(message: "La date de l'événement est obligatoire.")]
    #[Assert\GreaterThanOrEqual(
        value: "today",
        message: "La date de l'événement doit être au moins aujourd'hui."
    )]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La plage horaire est obligatoire.")]
    private ?string $timeSlot = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de l'événement est obligatoire.")]
    private ?string $eventName = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // Validation pour vérifier qu'une plage horaire est unique pour une date donnée
    #[Assert\Callback]
    public function validateUniqueTimeSlot(ExecutionContextInterface $context): void
    {
        $repository = $context->getObjectManager()->getRepository(Reservation::class);
        $existingReservation = $repository->findOneBy([
            'date' => $this->date,
            'timeSlot' => $this->timeSlot,
        ]);

        if ($existingReservation) {
            $context->buildViolation('Cette plage horaire est déjà réservée pour cette date.')
                ->atPath('timeSlot')
                ->addViolation();
        }
    }

    // Validation pour garantir qu'une réservation est effectuée au moins 24 heures à l'avance
    #[Assert\Callback]
    public function validateAdvanceReservation(ExecutionContextInterface $context): void
    {
        $now = new \DateTime();
        $interval = $now->diff($this->date);

        if ($interval->days < 1) {
            $context->buildViolation('Les réservations doivent être effectuées au moins 24 heures à l’avance.')
                ->atPath('date')
                ->addViolation();
        }
    }

    // Getter et Setter pour date
    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    // Getter et Setter pour timeSlot
    public function getTimeSlot(): ?string
    {
        return $this->timeSlot;
    }

    public function setTimeSlot(string $timeSlot): static
    {
        $this->timeSlot = $timeSlot;
        return $this;
    }

    // Getter et Setter pour eventName
    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;
        return $this;
    }

    // Getter et Setter pour user (relation ManyToOne)
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
