<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Master;
use App\Entity\Outfit;

class OutfitController extends AbstractController
{
    /**
     * @Route("/outfit", name="outfit_index")
     */
    public function index(): Response
    {
        return $this->render('outfit/index.html.twig', [
            'controller_name' => 'OutfitController',
        ]);
    }
    /**
     * @Route("/outfit/create", name="outfit_create", methods={"GET"})
     */
    public function create(): Response
    {
        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findAll();

        return $this->render('outfit/create.html.twig', [
            'masters' => $masters,
        ]);
    }
    /**
     * @Route("/outfit/store", name="outfit_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        $outfit = New Outfit;
        $outfit->
        setType($r->request->get('outfit_type'))->
        setColor($r->request->get('outfit_color'))->
        setSize($r->request->get('outfit_size'))->
        setAbout($r->request->get('outfit_about'))->
        setMasterId($r->request->get('outfit_master_id'));
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();

        return $this->redirectToRoute('outfit_index');
    }
}
