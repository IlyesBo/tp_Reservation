<?php

// src/Controller/UserController.php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function profile(UserInterface $user): Response
    {
        return $this->render('user/profile.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('/reserve', name: 'user_reserve')]
    public function reserve(Request $request): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservation->setUser($this->getUser());  // L'utilisateur connecté
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();

            $this->addFlash('success', 'Réservation effectuée avec succès');
            return $this->redirectToRoute('user_reservation_history'); // Modification ici
        }

        return $this->render('user/reserve.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reservations', name: 'user_reservation_history')] // La route doit être 'user_reservation_history'
    public function reservationHistory(ReservationRepository $reservationRepository, UserInterface $user): Response
    {
        // Récupérer toutes les réservations de l'utilisateur connecté
        $reservations = $reservationRepository->findBy(['user' => $user]);

        return $this->render('user/reservations.html.twig', [
            'reservations' => $reservations
        ]);
    }
}
