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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


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
            throw new BadRequestHttpException($errors);
    	}

        $listId = $this->listRepository->add($card);

		return $this->json($listId, Response::HTTP_CREATED);
    }

    #[Route('/positions', name: 'app_list_positions', methods: ['POST'])]
    public function positions(
        Request $request, 
        SerializerInterface $s,
        ValidatorInterface $validator
    )
    {
        $cards = $s->deserialize($request->getContent(), XList::class.'[]', 'json');
   
        $errors = $validator->validate($cards);

		if (count($errors) > 0) {
            throw new BadRequestHttpException($errors);
    	}

        $this->listRepository->positions($cards);

		return $this->json("OK", Response::HTTP_CREATED);
    }
}
