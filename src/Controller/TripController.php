<?php

namespace App\Controller;

use App\Repository\TripRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TripController extends AbstractController
{
    #[Route('/trips', name: 'app_trips', methods: ['GET'])]
    public function index(TripRepository $tR, PaginatorInterface $paginator, Request $request): Response
    {

        $pagination = $paginator->paginate(
            $tR->findAll(), 
            $request->query->getInt('page', 1), /* page number */
            18 /* limit per page */
        );

        return $this->render('trip/index.html.twig', [
            'trips' => $pagination,
            'title' => 'Trips',
            'description' => 'Les trips disponibles sur la plateforme. 100% made by you !',
        ]);
    }

    #[Route('/trip/{ref}', name:'app_trip', methods: ['GET'])]
    public function show(TripRepository $tR, string $ref): Response 
    {
        $trip = $tR->findOneBy(['ref' => $ref]);
        return $this->render('trip/show.html.twig', [
            'trip' => $trip,
            'title'=> $trip->getTitle(),
            'description' => $trip->getDescription() ?? $trip->getTitle(),
        ]);
    }
}
