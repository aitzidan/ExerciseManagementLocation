<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]

class ReservationController extends AbstractController
{
    private $MessageService;

    public function __construct(
        MessageService $MessageService , 
    ){
        $this->MessageService = $MessageService;
    }
    #[Route('/reservations', name:"post_reservation" ,methods: ['POST'])]
    public function reservations(Request $request): JsonResponse
    {
        $respObjects =array();
        $codeStatut = "ERROR";
        try{

            $codeStatut = "OK";

        }catch(\Exception $e){
            $codeStatut = "ERROR";
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }
}
