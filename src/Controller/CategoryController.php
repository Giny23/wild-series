<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/category', name: 'category_')]

class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
            return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    #[Route('/{categoryName}', methods: ['GET'], name: 'show')]
    public function show(CategoryRepository $categoryRepository, ProgramRepository $programRepository, string $categoryName): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException("Cette catégorie n'existe pas");
        } else {
            $programs = $programRepository->findBy(['category' => $category], ['id' => 'DESC'], 3);
        }

        return $this->render('category/show.html.twig', ['category' => $category, 'programs' => $programs]);
    }
}