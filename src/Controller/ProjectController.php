<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'api_project_index', methods: ['GET'])]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->json(
            $projectRepository->findAll(),
        );
    }

    #[Route('/add', name: 'app_project_add', methods: ['POST'])]
    public function add(
        Request $request, 
        ProjectRepository $projectRepository, 
        SerializerInterface $s
    ): Response
    {
        $project = $s->deserialize($request->getContent(), Project::class, 'json');
        $projectRepository->add($project, true);

        return $this->json("ok");
    }

    /* #[Route('/{id}', name: 'app_project_show', methods: ['GET'])] */
    /* public function show(Project $project): Response */
    /* { */
    /*     return $this->render('project/show.html.twig', [ */
    /*         'project' => $project, */
    /*     ]); */
    /* } */

    /* #[Route('/{id}/edit', name: 'app_project_edit', methods: ['GET', 'POST'])] */
    /* public function edit(Request $request, Project $project, ProjectRepository $projectRepository): Response */
    /* { */
    /*     $form = $this->createForm(ProjectType::class, $project); */
    /*     $form->handleRequest($request); */

    /*     if ($form->isSubmitted() && $form->isValid()) { */
    /*         $projectRepository->add($project, true); */

    /*         return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER); */
    /*     } */

    /*     return $this->renderForm('project/edit.html.twig', [ */
    /*         'project' => $project, */
    /*         'form' => $form, */
    /*     ]); */
    /* } */

    /* #[Route('/{id}', name: 'app_project_delete', methods: ['POST'])] */
    /* public function delete(Request $request, Project $project, ProjectRepository $projectRepository): Response */
    /* { */
    /*     if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) { */
    /*         $projectRepository->remove($project, true); */
    /*     } */

    /*     return $this->redirectToRoute('app_project_index', [], Response::HTTP_SEE_OTHER); */
    /* } */
}
