<?php

namespace App\Controller;

use App\Entity\Pins;
use App\Repository\PinsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PinsController extends AbstractController
{
    private $entityManager,
            $pinRepos,
            $entityPin;

    public function __construct(EntityManagerInterface $entityManager, PinsRepository $pinRepos)
    {
        $this->entityManager = $entityManager;
        $this->pinRepos = $pinRepos;
        $this->entityPin = new Pins;

    }
    #[Route('/', name: 'app_pins')]
    public function index(): Response
    {
        $pins = $this->pinRepos->findAll();
        return $this->render('pins/index.html.twig', compact('pins'));
    }

    #[Route('/create', name: 'app_create', methods: ['POST', 'GET'])]
    public function create(Request $request):Response
    {
        $form = $this->createFormBuilder($this->entityPin)
                ->add('title',null,['attr'=>['class'=>'form-controle','autofocus'=>true]])
                ->add('content',null,['attr'=>['row'=>5,'col'=>5]])
                ->getForm()
        ;

            $get = $form->createView() ;
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $this->pinRepos->save($this->entityPin, true);
                return $this->redirectToRoute('app_pins');
            }   
        return $this->render('pins/create.html.twig', compact('get'));
    }
}
