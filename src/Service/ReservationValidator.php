<?php

namespace App\Service;

use App\Entity\Reservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ReservationValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Vérifie si une réservation avec la même plage horaire existe déjà pour la date donnée.
     * 
     * @param Reservation $reservation
     * @throws BadRequestHttpException
     */
    public function validateReservation(Reservation $reservation): void
    {
        // Vérifier si une réservation existe déjà pour cette date et cette plage horaire
        $existingReservation = $this->entityManager->getRepository(Reservation::class)->findOneBy([
            'date' => $reservation->getDate(),
            'timeSlot' => $reservation->getTimeSlot(),
        ]);

        if ($existingReservation) {
            throw new BadRequestHttpException("Cette plage horaire est déjà réservée pour la date sélectionnée.");
        }

        // Vérifier si la réservation est faite au moins 24 heures avant la date de l'événement
        $now = new \DateTime();
        $interval = $now->diff($reservation->getDate());

        if ($interval->h < 24) {
            throw new BadRequestHttpException("Les réservations doivent être faites au moins 24 heures à l'avance.");
        }
    }
}
