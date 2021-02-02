<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Master;

class MasterController extends AbstractController
{
    /**
     * @Route("/master", name="master_index", methods={"GET"})
     */
    public function index(Request $r): Response
    {
        // $masters = $this->getDoctrine()
        // ->getRepository(Master::class)
        // ->findAll();

        $masters = $this->getDoctrine()
        ->getRepository(Master::class);
        if('name_az' == $r->query->get('sort')) {
            $masters = $masters->findBy([],['name' => 'asc', 'surname' => 'asc']);
        }
        else {
            $masters = $masters->findAll();
        }
        
        return $this->render('master/index.html.twig', [
            'masters' => $masters,
            'sortBy' => $r->query->get('sort') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
    /**
     * @Route("/master/create", name="master_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        $master_name = $r->getSession()->getFlashBag()->get('master_name', []);
        $master_surname = $r->getSession()->getFlashBag()->get('master_surname', []);

        return $this->render('master/create.html.twig', [
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'master_name' => $master_name[0] ?? '',
            'master_surname' => $master_surname[0] ?? ''
        ]);
    }
     /**
     * @Route("/master/store", name="master_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $submittedToken = $r->request->get('token');


        if (!$this->isCsrfTokenValid('', $submittedToken)) {
            $r->getSession()->getFlashBag()->add('errors', 'Blogas Tokenas CSRF');
            return $this->redirectToRoute('master_create');
        } 

        $master= New Master;
        $master->
        setName($r->request->get('master_name'))->
        setSurname($r->request->get('master_surname'));

        $errors = $validator->validate($master);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }

            $r->getSession()->getFlashBag()->add('master_name', $r->request->get('master_name'));
            $r->getSession()->getFlashBag()->add('master_surname', $r->request->get('master_surname'));

            return $this->redirectToRoute('master_create');
        }
       
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($master);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'master sekmingai prideras');

        return $this->redirectToRoute('master_index');
    }
    /**
     * @Route("/master/edit/{id}", name="master_edit", methods={"GET"})
     */
    public function edit(int $id, Request $r): Response
    {
        $master = $this->getDoctrine()
        ->getRepository(Master::class)
        ->find($id);


        $master_name = $r->getSession()->getFlashBag()->get('master_name', []);
        $master_surname = $r->getSession()->getFlashBag()->get('master_surname',[]);

        return $this->render('master/edit.html.twig', [
            'master' => $master,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'master_name' => $master_name[0] ?? '',
            'master_surname' => $master_surname[0] ?? ''
        ]);
    }
     /**
     * @Route("/master/update/{id}", name="master_update", methods={"POST"})
     */
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {
        $master = $this->getDoctrine()
        ->getRepository(Master::class)
        ->find($id);

        $master->
        setName($r->request->get('master_name'))->
        setSurname($r->request->get('master_surname'));

        $errors = $validator->validate($master);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('master_name', $r->request->get('master_name'));
            $r->getSession()->getFlashBag()->add('master_surname', $r->request->get('master_surname'));

            return $this->redirectToRoute('master_edit', ['id' => $master->getId()]);
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($master);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'masteris sekmingai pakeistas');

        return $this->redirectToRoute('master_index');
    }
     /**
     * @Route("/master/delete/{id}", name="master_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $master = $this->getDoctrine()
        ->getRepository(Master::class)
        ->find($id);

        if ($master->getOutfits()->count() > 0) {
            return new Response('Šio kūrėjo ištrinti negalima, nes turi gaminių.');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($master);
        $entityManager->flush();

        return $this->redirectToRoute('master_index');
    }

}
