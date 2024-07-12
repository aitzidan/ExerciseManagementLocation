<?php

namespace App\Controller;

use App\Services\MessageService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public $userServices;
    public $messageService;
    
    public function __construct(UserService $userServices , MessageService $messageService){
        $this->userServices = $userServices;
        $this->messageService = $messageService;
    }
    #[Route('/users/{id}/reservations', name: 'get_reservations_users')]
    public function reservations(Request $request , $id): JsonResponse
    {
        //TODO:Récuperation des reservation par users
        $respObjects = array();
        $codeStatut = "ERROR";
        try {
            
            $reservations = $this->userServices->getReservationByUser($id);
            $codeStatut="OK";
            $respObjects['data'] = $reservations;

        } catch (\Exception $e) {
            $codeStatut = "ERROR";
            $respObjects["err"] = $e->getMessage();
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->messageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }
}
