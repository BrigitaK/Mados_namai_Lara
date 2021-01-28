<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Master;

class MasterController extends AbstractController
{
    /**
     * @Route("/master", name="master_index", methods={"GET"})
     */
    public function index(): Response
    {
        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findAll();
        
        return $this->render('master/index.html.twig', [
            'masters' => $masters,
        ]);
    }
    /**
     * @Route("/master/create", name="master_create", methods={"GET"})
     */
    public function create(): Response
    {
        return $this->render('master/create.html.twig', [
        ]);
    }
     /**
     * @Route("/master/store", name="master_store", methods={"POST"})
     */
    public function store(Request $r): Response
    {
        $master= New Master;
        $master->
        setName($r->request->get('master_name'))->
        setSurname($r->request->get('master_surname'));

       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($master);
        $entityManager->flush();

        return $this->redirectToRoute('master_index');
    }
    /**
     * @Route("/master/edit/{id}", name="master_edit", methods={"GET"})
     */
    public function edit(int $id): Response
    {
        $master = $this->getDoctrine()
        ->getRepository(Master::class)
        ->find($id);

        return $this->render('master/edit.html.twig', [
            'master' => $master,
        ]);
    }

}
