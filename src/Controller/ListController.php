<?php

namespace App\Controller;

use App\Entity\XList;
use App\Repository\XListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use App\Exception\ValidationException;
use App\Exception\NotFoundException;
use App\Const\ErrorMessages;
use Doctrine\Common\Collections\ArrayCollection;


//TODO CSRF Token
#[Route('/list')]
class ListController extends AbstractController
{
    private $listRepository;

    public function __construct(XListRepository $pr)
    {
        $this->listRepository = $pr; 
    }

    #[Route('/', name: 'app_list_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->json([
            // We send the MaxID to avoid the client to wait the object
            // persistence to continue with it's new ID.
            'lists' => $this->listRepository->findAll()
        ], Response::HTTP_OK);
    }

    #[Route('/', name: 'app_list_add', methods: ['POST'])]
    public function add(
        Request $request, 
        SerializerInterface $s,
        ValidatorInterface $validator
    ): Response
    {
        $card = $s->deserialize($request->getContent(), XList::class, 'json');

        $errors = $validator->validate($card);

		if (count($errors) > 0) {
            throw new ValidationException(
                ErrorMessages::validationMessage($errors)
            );
    	}

        $listId = $this->listRepository->add($card, true);

		return $this->json($listId, Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'app_list_one', methods: ['GET'])]
    public function show(
        int $id,
    ): Response
    {
        if (($list = $this->listRepository->find($id)) == null) 
        {
            throw new NotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->listRepository->getClassName()
                )
            );
        }

		return $this->json($list, Response::HTTP_OK, []);
    }

    #[Route('/{id}', name: 'app_list_edit', methods: ['PUT'])]
    public function edit(
        int $id, 
        Request $request, 
        SerializerInterface $s,
        ValidatorInterface $v
    ): Response
    {
        if (($list = $this->listRepository->find($id)) == null) 
        {
            throw new NotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->listRepository->getClassName()
                )
            );
        }

        $s->deserialize(
            $request->getContent(),
            xList::class, 
            'json', 
            [AbstractNormalizer::OBJECT_TO_POPULATE => $list]
        );

        $errors = $v->validate($list);
        
		if (count($errors) > 0) {
            throw new CustomValidationException(
                ErrorMessages::validationMessage($errors)
            );
    	}

        $this->listRepository->add($list, true);

		return $this->json($this->getParameter('api_constants.messages.success'), Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'app_list_delete', methods: ['DELETE'])]
    public function delete(int $id): Response
    {
        if (($list = $this->listRepository->find($id)) == null) 
        {
            throw new NotFoundException(
                ErrorMessages::entityNoFound(
                    $id, $this->listRepository->getClassName()
                )
            );
        }

        $this->listRepository->remove($list, true);

		return $this->json($this->getParameter('api_constants.messages.success'), Response::HTTP_OK);
    }

    #[Route('/positions', name: 'app_list_positions', methods: ['POST'])]
    public function positions(
        Request $request, 
        SerializerInterface $s,
        ValidatorInterface $validator
    )
    {
        $cards = $s->deserialize($request->getContent(), XList::class.'[]', 'json');
        $count = count($cards);
        $cards = new ArrayCollection($cards);
        $order = 1;

        if ($count !=  $this->listRepository->countCards()) {
            throw new ValidationException('Cards Number crafted');
        }

        $cards->map(function($card) use ($count, &$order) {
            if ($order != $card->getPosition()) {
                throw new ValidationException('Order crafted');
            }
            $order++;
        }); 

   
        $errors = $validator->validate($cards);

		if (count($errors) > 0) {
            throw new ValidationException(
                ErrorMessages::validationMessage($errors)
            );
    	}

        $this->listRepository->positions($cards, true);

		return $this->json("OK", Response::HTTP_CREATED);
    }
}
