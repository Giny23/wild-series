<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
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
    public function show(Program $program): Response
    {
        //$program = $programRepository->findOneBy(['id' => $id]);
        $seasons = $program->getSeasons();

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program, 'seasons' => $seasons
        ]);
    }
    #[Route('/{program<\d+>}/season/{season<\d+>}', methods: ['GET'], name: 'showSeason')]
    public function showSeason(Program $program, Season $season) : Response
    {
        $episodes = $season->getEpisodes();
        return $this->render('program/season_show.html.twig', [
            'program' => $program, 'season' => $season, 'episodes' => $episodes
        ]);
    }

    #[Route('/{program<\d+>}/season/{season<\d+>}/episode/{episode<\d+>}', methods: ['GET'], name: 'showEpisode')]
    public function showEpisode(Program $program, Season $season, Episode $episode)
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program, 'season' => $season, 'episode' => $episode
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