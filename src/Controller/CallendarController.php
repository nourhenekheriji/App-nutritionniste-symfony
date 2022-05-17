<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Repository\RendezVousRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CallendarController extends AbstractController
{
    /**
     * @Route("/callendar", name="callendar")
     */
    public function index( RendezVousRepository $rendezVousRepository): Response
    {
        $client = $this->getDoctrine()
            ->getRepository(Personne::class)
            ->find(1);
        $rendezs = $rendezVousRepository->findBy(array('idClient'=>$client));

        $rdvs=[];
        foreach ($rendezs as $rendez){
            $rdvs[]= [
                'id'=> $rendez->getId(),
                'start'=> $rendez->getStart()->format('Y-m-d H:i:s'),
                'end'=> $rendez->getEnd()->format('Y-m-d H:i:s'),
                'title'=> $rendez->getTitle(),
                'description'=> $rendez->getDescription(),
                'backgroundColor'=> $rendez->getBackgroundColor(),
                'borderColor'=> $rendez->getBorderColor(),
                'textColor'=> $rendez->getTextColor(),
            ];

        }
        $data = json_encode($rdvs);


        return $this->render('callendar/index.html.twig',compact('data')
        );
    }



    /**
     * @Route("/doctorCalender", name="doctorCalender")
     */
    public function doctorRendezvous( RendezVousRepository $rendezVousRepository): Response
    {
        $client = $this->getDoctrine()
            ->getRepository(Personne::class)
            ->find(2);
        $rendezs = $rendezVousRepository->findBy(array('idMedecin'=>$client));

        $rdvs=[];
        foreach ($rendezs as $rendez){
            $rdvs[]= [
                'id'=> $rendez->getId(),
                'start'=> $rendez->getStart()->format('Y-m-d H:i:s'),
                'end'=> $rendez->getEnd()->format('Y-m-d H:i:s'),
                'title'=> $rendez->getTitle(),
                'description'=> $rendez->getDescription(),
                'backgroundColor'=> $rendez->getBackgroundColor(),
                'borderColor'=> $rendez->getBorderColor(),
                'textColor'=> $rendez->getTextColor(),
            ];

        }
        $data = json_encode($rdvs);


        return $this->render('callendar/DoctorCalender.html.twig',compact('data')
        );
    }
}

