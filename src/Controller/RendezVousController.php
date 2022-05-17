<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/rendez")
 */
class RendezVousController extends AbstractController
{
    /**
     * @Route("/", name="rendez_vous_index", methods={"GET"})
     */
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        $client = $this->getDoctrine() 
            ->getRepository(Personne::class)
            ->find(1);
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findBy(array('idClient'=>$client)),
        ]);
    }

    /**
     * @Route("/new", name="rendez_vous_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezVou = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);
        $client = $this->getDoctrine()
            ->getRepository(Personne::class)
            ->find(1);
        $medecin = $this->getDoctrine()
            ->getRepository(Personne::class)
            ->find(2);

        if ($form->isSubmitted() && $form->isValid()) {
            $rendezVou->setBackgroundColor("#d41616");
            $rendezVou->setTextColor("#000000");
            $rendezVou->setBorderColor("#060505");
            $rendezVou->setIdClient($client);
            $rendezVou->setIdMedecin($medecin);
            $entityManager->persist($rendezVou);
            $entityManager->flush();

            return $this->redirectToRoute('rendez_vous_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('rendez_vous/new.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rendez_vous_show", methods={"GET"})
     */
    public function show(RendezVous $rendezVou): Response
    {
        return $this->render('rendez_vous/show.html.twig', [
            'rendez_vou' => $rendezVou,
        ]);
    }


    /**
     * @Route("/{id}/editrendezvous", name="rendez_edit", methods={"PUT"})
     */
    public function majEvent(Request $request, ?RendezVous $rendezVous): Response
    {

        $donnees =json_decode($request->getContent());

        if (isset($donnees->title) && !empty($donnees->title) &&
            isset($donnees->start) && !empty($donnees->start) &&
            isset($donnees->end) && !empty($donnees->end) &&
            isset($donnees->description) && !empty($donnees->description) &&
            isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
            isset($donnees->borderColor) && !empty($donnees->borderColor) &&
            isset($donnees->textColor) && !empty($donnees->textColor)
           ){

            $code = 200 ;
            if(!$rendezVous){
                $rendezVous = new RendezVous();
                $code = 201;
            }

            $rendezVous->setTitle($donnees->title);
            $rendezVous->setStart(new \DateTime($donnees->start));
            $rendezVous->setEnd(new \DateTime($donnees->end));
            $rendezVous->setDescription($donnees->description);
            $rendezVous->setBorderColor($donnees->borderColor);
            $rendezVous->setTextColor($donnees->textColor);
            $rendezVous->setBackgroundColor($donnees->backgroundColor);
            $em = $this->getDoctrine()->getManager();
            $em->persist($rendezVous);
            $em->flush();

            return new Response('ok',$code);

        }

        else{
            return new Response('donnees incompléte',404);
        }

    }



    /**
     * @Route("/{id}/edit", name="rendez_vous_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, RendezVous $rendezVou, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RendezVousType::class, $rendezVou);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendez_vous/edit.html.twig', [
            'rendez_vou' => $rendezVou,
            'form' => $form->createView(),
        ]);
    }

   /**
     * @Route("/delete/{id}", name="rendezvous_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rd = $em->getRepository(RendezVous::class)->find($id);
        $em->remove($rd);
        $em->flush();
        return $this->redirectToRoute('rendez_vous_index');
    }

 ///**
    // * @Route("/delete/{id}", name="rendezvous_delete", methods="DELETE", options={"expose"=true})
    // * @param Request $request
    // * @param RendezVous $rendezVou
    // * @return Response
    // */
    //public function delete(Request $request, RendezVous $rendezVou): Response
    //{
        //if ($request->isXmlHttpRequest()) {
          //  if ($this->isCsrfTokenValid('delete'.$rendezVou->getId(), $request->request->get('_token'))) {
            //    $em = $this->getDoctrine()->getManager();
              //  $em->getConnection()->beginTransaction();

//                $em->remove($rendezVou);
  //              $em->flush();

    //            $em->commit();
      //          return new JsonResponse([
        //            'type' => 'success',
          //          'message'   =>  'item was removed'
            //    ], 200);
            //}
        //}
        //return new JsonResponse([
          //  'type'      => 'error',
            //'message'   => 'This is only accesible in AJAX'
        //], 500);
    //}

//}
        }