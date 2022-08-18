<?php

namespace App\Controller;

use App\Entity\ProjectList;
use App\Form\ProjectListType;
use App\Repository\ProjectListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use App\Exception\CustomValidationException;

#[Route('/project/list')]
class ProjectListController extends AbstractController
{
    #[Route('/', name: 'app_project_list_index', methods: ['GET'])]
    public function index(ProjectListRepository $projectListRepository): Response
    {
        return $this->json(
            $projectListRepository->findAll(),
        );
    }

    #[Route('/add', name: 'app_project_list_new', methods: ['POST'])]
    public function add(
        Request $request, 
        ProjectListRepository $projectListRepository,
        SerializerInterface $s,
        ValidatorInterface $validator
    ): Response
    {
        $list = $s->deserialize($request->getContent(), ProjectList::class, 'json');

        $errors = $validator->validate($list);

		if (count($errors) > 0) {
			throw new CustomValidationException((string)$errors);
    	}

        $projectListRepository->add($list, true);

		return $this->json("Ok", 201);
    }

    #[Route('/{id}', name: 'app_project_list_show', methods: ['GET'])]
    public function show(
        int $id,
        ProjectListRepository $projectListRepository,
    ): Response
    {
        if (($projectList = $projectListRepository->find($id)) == null) 
			throw new CustomValidationException("Not found, try to fetch id : $id from ". get_class($this));

		return $this->json($projectList, 200);
    }

    /* #[Route('/{id}/edit', name: 'app_project_list_edit', methods: ['GET', 'POST'])] */
    /* public function edit(Request $request, ProjectList $projectList, ProjectListRepository $projectListRepository): Response */
    /* { */
    /*     $form = $this->createForm(ProjectListType::class, $projectList); */
    /*     $form->handleRequest($request); */

    /*     if ($form->isSubmitted() && $form->isValid()) { */
    /*         $projectListRepository->add($projectList, true); */

    /*         return $this->redirectToRoute('app_project_list_index', [], Response::HTTP_SEE_OTHER); */
    /*     } */

    /*     return $this->renderForm('project_list/edit.html.twig', [ */
    /*         'project_list' => $projectList, */
    /*         'form' => $form, */
    /*     ]); */
    /* } */

    /* #[Route('/{id}', name: 'app_project_list_delete', methods: ['POST'])] */
    /* public function delete(Request $request, ProjectList $projectList, ProjectListRepository $projectListRepository): Response */
    /* { */
    /*     if ($this->isCsrfTokenValid('delete'.$projectList->getId(), $request->request->get('_token'))) { */
    /*         $projectListRepository->remove($projectList, true); */
    /*     } */

    /*     return $this->redirectToRoute('app_project_list_index', [], Response::HTTP_SEE_OTHER); */
    /* } */
}
