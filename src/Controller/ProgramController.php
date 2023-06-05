<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Repository\CommentRepository;
use App\Service\ProgramDuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProgramRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
    #[Route('/new', methods: ['GET', 'POST'], name: 'new')]
    public function new(Request $request, ProgramRepository $programRepository, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser()->getEmail());
            $programRepository->save($program, true);
            $email = (new Email())
                ->from('expediteur@example.com')
                ->to('destinataire@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/email.html.twig', ['program' => $program]));
            $mailer->send($email);
            $this->addFlash('success', 'Nouvelle série ajoutée');
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'form' => $form,
        ]);
    }
    #[Route('/{slug}', methods: ['GET'], name: 'show')]
    public function show(Program $program, ProgramDuration $duration): Response
    {
        //$program = $programRepository->findOneBy(['id' => $id]);
        $seasons = $program->getSeasons();
        $programDuration = $duration->calculate($program);
        if (is_null($programDuration)) {
            $programDuration = 0;
        }

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program, 'seasons' => $seasons, 'programDuration' => $programDuration
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

    #[Route('/{program<\d+>}/season/{season<\d+>}/episode/{episode<\d+>}', methods: ['GET', 'POST'], name: 'showEpisode')]
    public function showEpisode(Program $program, Season $season, Episode $episode, ProgramDuration $duration, Request $request, CommentRepository $commentRepository) : Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setEpisode($episode);
            $commentRepository->save($comment, true);
        }
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'duration' => $duration->calculate($program),
            'comments' => $commentRepository->findBy(['episode' => $episode]),
            'form' => $form,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->getUser() !== $program->getOwner()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cette série');
        }

        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $programRepository->save($program, true);
            $this->addFlash('success', "La série a bien été mise à jour");

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }
    #[Route('/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Program $program, ProgramRepository $programRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$program->getId(), $request->request->get('_token'))) {
            $programRepository->remove($program, true);
            $this->addFlash('danger', "La série a bien été supprimée");
        }

        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }
}