<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategoryController extends AbstractController
{
    // #[IsGranted('ROLE_USER')]
    #[Route('/category', name: 'app_category', methods: ['GET', 'POST'])]
    public function index(CategoryRepository $cr): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $cr->findAll(),
        ]);
    }
}
