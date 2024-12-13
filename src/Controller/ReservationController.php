<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Service\ReservationValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ReservationValidator $reservationValidator;

    public function __construct(EntityManagerInterface $entityManager, ReservationValidator $reservationValidator)
    {
        $this->entityManager = $entityManager;
        $this->reservationValidator = $reservationValidator;
    }

    #[Route('/reservation', name: 'create_reservation', methods: ['POST'])]
    public function create(Request $request): Response
    {
        $reservation = new Reservation();
        $reservation->setDate(new \DateTime($request->get('date')));
        $reservation->setTimeSlot($request->get('timeSlot'));
        $reservation->setEventName($request->get('eventName'));
        $reservation->setUser($this->getUser());

        try {
            $this->reservationValidator->validateReservation($reservation);
            $this->entityManager->persist($reservation);
            $this->entityManager->flush();

            return $this->redirectToRoute('user_reservations');
        } catch (\Exception $e) {
            return new Response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    #[Route('/user/reservations', name: 'user_reservations', methods: ['GET'])]
    public function reservations(): Response
    {
        $reservations = $this->entityManager->getRepository(Reservation::class)
                                            ->findBy(['user' => $this->getUser()]);

        return $this->render('user/reservations.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}


