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
use App\Exception\CustomNotFoundException;
use App\Service\ErrorMessages;
use App\Interface\SerializationContextInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

//TODO CSRF Token
#[Route('/project/list')]
class ProjectListController extends AbstractController
{
    private $_sc;
    private $_pr;

    public function __construct(SerializationContextInterface $sc, ProjectListRepository $pr)
    {
        $this->_sc = $sc; 
        $this->_pr = $pr; 
    }

    #[Route('/', name: 'app_project_list_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json($this->_pr->findAll(), 200);
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

        $this->_pr->add($list, true);

		return $this->json("Ok", 201);
    }

    #[Route('/{id}', name: 'app_project_list_show', methods: ['GET'])]
    public function show(
        int $id,
    ): Response
    {
        if (($projectList = $this->_pr->find($id)) == null) 
        {
            throw new CustomNotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->_pr->getClassName()
                )
            );
        }

		return $this->json($projectList, 200, [], $this->_sc->getContext());
    }

    #[Route('/{id}/edit', name: 'app_project_list_edit', methods: ['POST'])]
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

        $this->_sc->addObjectToPopulate($projectList);

        $s->deserialize(
            $request->getContent(),
            ProjectList::class, 
            'json', 
            $this->_sc->getContext()
        );

        $errors = $v->validate($projectList);
        
		if (count($errors) > 0) {
            throw new CustomValidationException(
                ErrorMessages::validationMessage($errors)
            );
    	}

        $pr->add($projectList, true);

		return $this->json("Ok", 202);
    }

    #[Route('/{id}/delete', name: 'app_project_list_delete', methods: ['POST'])]
    public function delete(int $id): Response
    {
        if (($projectList = $this->_pr->find($id)) == null) 
        {
            throw new CustomNotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->_pr->getClassName()
                )
            );
        }

        $this->_pr->remove($projectList, true);

		return $this->json("Ok", 202);
    }
}
