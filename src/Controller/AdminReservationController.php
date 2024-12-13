<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminReservationController extends AbstractController
{
    // Liste de toutes les réservations
    #[Route('/admin/reservations', name: 'admin_reservations')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findAll();
        return $this->render('admin_reservation/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    // Supprimer une réservation
    #[Route('/admin/reservation/{id}/delete', name: 'admin_reservation_delete')]
    public function delete(Reservation $reservation, EntityManagerInterface $em): Response
    {
        $em->remove($reservation);
        $em->flush();
        $this->addFlash('success', 'Réservation supprimée avec succès.');
        return $this->redirectToRoute('admin_reservations');
    }
}


