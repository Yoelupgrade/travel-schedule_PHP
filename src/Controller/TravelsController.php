<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\TravelForm;
use App\Entity\Travels;
use Doctrine\ORM\EntityManagerInterface;

class TravelsController extends AbstractController{
    
    /**
     * @Route("/", name="home")
     */
    public function homeTravels (){
       return $this->render('ListTravels/home.html.twig');  
    }

    /**
     * @Route("/travels", name="travels")
     */
    public function listTravels (EntityManagerInterface $doctrine){
        $repository = $doctrine->getRepository(Travels::class);	
        $travels=$repository->findAll();
       return $this->render('ListTravels/listTravels.html.twig', ["travels"=>$travels]);  
    }

    /**
     * @Route("/travels/{id}", name="showTravel")
     */
    public function showTravels ($id, EntityManagerInterface $doctrine) {
        
        $repository = $doctrine->getRepository(Travels::class);
        $travel = $repository->find($id);

        return $this->render('ListTravels/singleTravel.html.twig', ["travel"=>$travel]);
    }

    /**
     * @Route("/create/travel", name="insertTravel")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addTravel (Request $request, EntityManagerInterface $doctrine)
    {
        $form = $this->createForm(travelForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $travel = $form->getData();
            $doctrine->persist($travel);
            $doctrine->flush();
            $this->addFlash('exito', 'travel insertado correctamente');
        }
        return $this->renderForm("ListTravels/createTravel.html.twig", ["travelForm" => $form]);
    }


    /**
     * @Route("/createTravel")
     */
    public function insertTravel(EntityManagerInterface $doctrine){
    
        $travel = new Travels();
        $travel-> setName('Laponia');
        $travel-> setImage('https://viajes.nationalgeographic.com.es/medio/2018/02/27/laponia__1280x720.jpg'); 
        // $travel-> setItinerario('');
        // $travel-> setDescription('');
        // $travel-> setDate('');

        $travel2 = new Travels();
        $travel2-> setName('New York');
        $travel2-> setImage('https://estaticos.muyinteresante.es/uploads/images/test/60b4a8d15cafe819e843397a/empire-state-redes.jpg');
        // $travel2-> setItinerario('');
        // $travel2-> setDescription('');
        // $travel2-> setDate('');

        $travel3 = new Travels();
        $travel3-> setName('Ciudad de México');
        $travel3-> setImage('https://cdn.getyourguide.com/img/location/5c9f29710b6f9.jpeg/88.jpg');
        // $travel3-> setItinerario('');
        // $travel3-> setDescription('');
        // $travel3-> setDate('');
        
        $doctrine->persist($travel);
        $doctrine->persist($travel2);
        $doctrine->persist($travel3);
        $doctrine->flush(); //inserta las ciudades

        return $this->render("base.html.twig");
    }
}
