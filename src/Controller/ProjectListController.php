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
use App\Exception\ClientEntityIdMismatch;
use App\Exception\CustomNotFoundException;
use App\Const\ErrorMessages;
use App\Interface\SerializationContextInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

//TODO CSRF Token
#[Route('/project/list')]
class ProjectListController extends AbstractController
{
    private $projectListRepository;

    public function __construct(ProjectListRepository $pr)
    {
        $this->projectListRepository = $pr; 
    }

    #[Route('/', name: 'app_project_list_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json([
            // We send the MaxID to avoid the client to wait the object
            // persistence to continue with it's new ID.
            'lists' => $this->projectListRepository->findAll()
        ], Response::HTTP_OK);
    }

    #[Route('/add', name: 'app_project_list_new', methods: ['POST'])]
    public function add(
        Request $request, 
        SerializerInterface $s,
        ValidatorInterface $validator
    ): Response
    {
        $list = $s->deserialize($request->getContent(), ProjectList::class, 'json');

        $errors = $validator->validate($list);

		if (count($errors) > 0) {
            throw new CustomValidationException(
                ErrorMessages::validationMessage($errors)
            );
    	}

        $listId = $this->projectListRepository->add($list, true);

		return $this->json($listId, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_project_list_show', methods: ['GET'])]
    public function show(
        int $id,
    ): Response
    {
        if (($projectList = $this->projectListRepository->find($id)) == null) 
        {
            throw new CustomNotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->projectListRepository->getClassName()
                )
            );
        }

		return $this->json($projectList, Response::HTTP_OK, []);
    }

    #[Route('/{id}/edit', name: 'app_project_list_edit', methods: ['PUT'])]
    public function edit(
        int $id, 
        Request $request, 
        ProjectListRepository $pr,
        SerializerInterface $s,
        ValidatorInterface $v
    ): Response
    {
        if (($projectList = $pr->find($id)) == null) 
        {
            throw new CustomNotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $pr->getClassName()
                )
            );
        }

        $s->deserialize(
            $request->getContent(),
            ProjectList::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $projectList]
        );

        $errors = $v->validate($projectList);
        
		if (count($errors) > 0) {
            throw new CustomValidationException(
                ErrorMessages::validationMessage($errors)
            );
    	}

        $pr->add($projectList, true);

		return $this->json($this->getParameter('api_constants.messages.success'), Response::HTTP_OK);
    }

    #[Route('/{id}/delete', name: 'app_project_list_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        if (($projectList = $this->projectListRepository->find($id)) == null) 
        {
            throw new CustomNotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->projectListRepository->getClassName()
                )
            );
        }

        $this->projectListRepository->remove($projectList, true);

		return $this->json($this->getParameter('api_constants.messages.success'), Response::HTTP_OK);
    }
}
