<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Master;
use App\Entity\Outfit;

class OutfitController extends AbstractController
{
    /**
     * @Route("/outfit", name="outfit_index")
     */
    public function index(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // $outfits = $this->getDoctrine()
        // ->getRepository(Outfit::class)
        // ->findAll();
        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findBy([],['name' => 'asc', 'surname' => 'asc']);

        $outfits = $this->getDoctrine()
        ->getRepository(Outfit::class);
        if(null !== $r->query->get('type')){
            $outfits = $outfits->findBy(['type' => $r->query->get('type')]);
        }
        elseif ($r->query->get('type') == 0) {
            $outfits = $outfits->findAll(); 
        }
        else {
            $outfits = $outfits->findAll();
        };
        
        return $this->render('outfit/index.html.twig', [
            'outfits' => $outfits,
            'masters' => $masters,
            'outfitType' => $r->query->get('type') ?? 'default',
            'sortBy' => $r->query->get('sort') ?? 'default',
            'success' => $r->getSession()->getFlashBag()->get('success', [])
        ]);
    }
    /**
     * @Route("/outfit/create", name="outfit_create", methods={"GET"})
     */
    public function create(Request $r): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $outfit_type = $r->getSession()->getFlashBag()->get('outfit_type', []);
        $outfit_color = $r->getSession()->getFlashBag()->get('outfit_color', []);
        $outfit_size = $r->getSession()->getFlashBag()->get('outfit_size', []);
        $outfit_about = $r->getSession()->getFlashBag()->get('outfit_about', []);

        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findBy([],['name' => 'asc', 'surname' => 'asc']);

        return $this->render('outfit/create.html.twig', [
            'masters' => $masters,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'outfit_type' => $outfit_type[0] ?? '',
            'outfit_color' => $outfit_color[0] ?? '',
            'outfit_size' => $outfit_size[0] ?? '',
            'outfit_about' => $outfit_about[0] ?? ''
        ]);
    }
    /**
     * @Route("/outfit/store", name="outfit_store", methods={"POST"})
     */
    public function store(Request $r, ValidatorInterface $validator): Response
    {
        $master = $this->getDoctrine()
        ->getRepository(Master::class)
        ->find($r->request->get('outfit_master_id'));

        $outfit = New Outfit;
        $outfit->
        setType($r->request->get('outfit_type'))->
        setColor($r->request->get('outfit_color'))->
        setSize((int)$r->request->get('outfit_size'))->
        setAbout($r->request->get('outfit_about'))->
        setMaster($master);
       
        $errors = $validator->validate($outfit);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('outfit_type', $r->request->get('outfit_type'));
            $r->getSession()->getFlashBag()->add('outfit_color', $r->request->get('outfit_color'));
            $r->getSession()->getFlashBag()->add('outfit_size', $r->request->get('outfit_size'));
            $r->getSession()->getFlashBag()->add('outfit_about', $r->request->get('outfit_about'));

            return $this->redirectToRoute('outfit_create');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'outfit sekmingai pridetas.');

        return $this->redirectToRoute('outfit_index');
    }
    /**
     * @Route("/outfit/edit/{id}", name="outfit_edit", methods={"GET"})
     */
    public function edit(int $id, Request $r): Response
    {
        $outfit = $this->getDoctrine()
        ->getRepository(Outfit::class)
        ->find($id);

        $masters = $this->getDoctrine()
        ->getRepository(Master::class)
        ->findAll();

        $outfit_type = $r->getSession()->getFlashBag()->get('outfit_type', []);
        $outfit_color = $r->getSession()->getFlashBag()->get('outfit_color', []);
        $outfit_size = $r->getSession()->getFlashBag()->get('outfit_size', []);
        $outfit_about = $r->getSession()->getFlashBag()->get('outfit_about', []);

        return $this->render('outfit/edit.html.twig', [
            'outfit' => $outfit,
            'masters' => $masters,
            'errors' => $r->getSession()->getFlashBag()->get('errors', []),
            'outfit_type' => $outfit_type[0] ?? '',
            'outfit_color' => $outfit_color[0] ?? '',
            'outfit_size' => $outfit_size[0] ?? '',
            'outfit_about' => $outfit_about[0] ?? ''
        ]);
    }
       /**
     * @Route("/outfit/update/{id}", name="outfit_update", methods={"POST"})
     */
    public function update(Request $r, $id, ValidatorInterface $validator): Response
    {
        $outfit = $this->getDoctrine()
        ->getRepository(Outfit::class)
        ->find($id);

        $master = $this->getDoctrine()
         ->getRepository(Master::class)
         ->find($r->request->get('outfit_master_id'));

        $outfit
        ->setType($r->request->get('outfit_type'))
        ->setColor($r->request->get('outfit_color'))
        ->setSize($r->request->get('outfit_size'))
        ->setAbout($r->request->get('outfit_about'))
        ->setMaster($master);
        $errors = $validator->validate($outfit);


        if (count($errors) > 0) {

            foreach($errors as $error) {
                $r->getSession()->getFlashBag()->add('errors', $error->getMessage());
            }
            $r->getSession()->getFlashBag()->add('outfit_type', $r->request->get('outfit_type'));
            $r->getSession()->getFlashBag()->add('outfit_color', $r->request->get('outfit_color'));
            $r->getSession()->getFlashBag()->add('outfit_size', $r->request->get('outfit_size'));
            $r->getSession()->getFlashBag()->add('outfit_about', $r->request->get('outfit_about'));

            return $this->redirectToRoute('outfit_create');
        }
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($outfit);
        $entityManager->flush();

        $r->getSession()->getFlashBag()->add('success', 'outfit sekmingai pakeistas.');

        //grazinu redirect
        return $this->redirectToRoute('outfit_index');
    }
      /**
     * @Route("/outfit/delete/{id}", name="outfit_delete", methods={"POST"})
     */
    public function delete($id): Response
    {
        $outfit = $this->getDoctrine()
        ->getRepository(Outfit::class)
        ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($outfit);
        $entityManager->flush();

        //grazinu redirect
        return $this->redirectToRoute('outfit_index');
    }

}
