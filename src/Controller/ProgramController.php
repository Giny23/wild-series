<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository): Response
    {
        $programs = $programRepository->findAll();
        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }
    #[Route('/{id<\d+>}', methods: ['GET'], name: 'show')]
    public function show(ProgramRepository $programRepository, int $id): Response
    {
        $program = $programRepository->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program
        ]);
    }

    /* a configurer dans les prochaines quêtes
    #[Route('/new', methods: ['GET', 'POST'], name: 'new')]
    public function new(): Response
    {
        return $this->render('program/index.html.twig.twig', [
            'website' => 'Wild Series',
        ]);
    }
    #[Route('/{id<\d+>}', methods: ['DELETE'], name: 'delete')]
    public function new(): Response
    {
        return $this->render('program/index.html.twig.twig', [
            'website' => 'Wild Series',
        ]);
    } */
}