<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Repository\UserRepository;
use App\Services\AuthentificationService;
use App\Services\ControllerService;
use App\Services\MessageService;
use App\Services\ReservationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]

class ReservationController extends AbstractController
{
    private $MessageService;
    private $AuthService;
    private $reservationService;
    private $carsRepo;
    private $userRepo;
    private $controllerService;

    public function __construct(
        MessageService $MessageService , 
        AuthentificationService $AuthService,
        ReservationService $reservationService,
        CarRepository $carsRepo,
        UserRepository $userRepo,
        ControllerService $controllerService,
    ){
        $this->MessageService = $MessageService;
        $this->AuthService = $AuthService;
        $this->reservationService = $reservationService;
        $this->carsRepo = $carsRepo;
        $this->userRepo = $userRepo;
        $this->controllerService = $controllerService;
    }
    #[Route('/reservations', name:"post_reservation" ,methods: ['POST'])]
    public function reservations(Request $request): JsonResponse
    {
        //TODO:Création de reservation 
        $respObjects = array();
        $codeStatut = "ERROR";
        try {
            $dataRequest = json_decode($request->getContent(), true);
            $dateStart = new \DateTime($dataRequest['dateStart']);
            $dateEnd = new \DateTime($dataRequest['dateEnd']);
            $idCar = $dataRequest['idCar'];
            $idUser = $this->AuthService->getUserId($request);

            //TODO : Vérification disponbilité de véhecule
            $checkAvailable = $this->carsRepo->checkCarsAvailable($idCar, $dateStart, $dateEnd);
            
            //TODO:Vérification des dates
            $checkDate = $this->controllerService->checkDate($dateStart, $dateEnd);

            if ($checkDate) {
                if ($checkAvailable) {
                    $User = $this->userRepo->getUser($idUser);
                    $Cars = $this->carsRepo->getOneCar($idCar);
                    
                    $this->reservationService->addReservation($dateStart, $dateEnd, $User, $Cars);
                    $codeStatut = "OK";
                } else {
                    $codeStatut = "CAR_NOT_AVAILABLE";
                }
            } else {
                $codeStatut = "ERROR_DATE";
            }
        } catch (\Exception $e) {
            $codeStatut = "ERROR";
            $respObjects["err"] = $e->getMessage();
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }

    #[Route('/reservations/{id}', name:"put_reservation" ,methods: ['PUT'])]
    public function updateReservation(Request $request , $id): JsonResponse
    {
        //TODO:Modification de reservation 
        $respObjects = array();
        $codeStatut = "ERROR";
        try {
            $dataRequest = json_decode($request->getContent(), true);
            $reservation = $this->reservationService->getReservation($id);
            $idUser = $this->AuthService->getUserId($request);
            
            if($reservation){
                if($reservation->getIdUser()->getId() == $idUser){
                    $dateStart = new \DateTime($dataRequest['dateStart']);
                    $dateEnd = new \DateTime($dataRequest['dateEnd']);
                    $idCar = $dataRequest['idCar'];

                    //TODO:Vérification disponbilité de véhecule
                    $checkAvailable = $this->carsRepo->checkCarsAvailableForUpdate($idCar, $dateStart, $dateEnd , $reservation->getId());
                    
                    //TODO:Vérification des dates
                    $checkDate = $this->controllerService->checkDate($dateStart, $dateEnd);
                    if ($checkDate) {
                        if ($checkAvailable) {
                        
                            $this->reservationService->updateReservation($reservation,$dateStart, $dateEnd);
                            $codeStatut = "OK";
        
                        } else {
                            $codeStatut = "CAR_NOT_AVAILABLE";
                        }
                    } else {
                        $codeStatut = "ERROR_DATE";
                    }
                }else{
                    $codeStatut = "ERROR";
                }
            }else{
                $codeStatut = "ERROR_AUTHORIZATION_RESERVATION";
            }
        } catch (\Exception $e) {
            $codeStatut = "ERROR";
            $respObjects["err"] = $e->getMessage();
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }

    #[Route('/reservations/{id}', name:"cancel_reservation" ,methods: ['DELETE'])]
    public function cancelReservation(Request $request , $id): JsonResponse
    {
        //TODO:Annulation de réservation
        $respObjects = array();
        $codeStatut = "ERROR";
        try {
            $dataRequest = json_decode($request->getContent(), true);
            $reservation = $this->reservationService->getReservation($id);
            $idUser = $this->AuthService->getUserId($request);
            if($reservation){
                if($reservation->getIdUser()->getId() == $idUser){
                    $this->reservationService->cancelReservation($reservation);
                    $codeStatut="OK";
                }else{
                    $codeStatut = "ERROR_AUTHORIZATION_RESERVATION";
                }

            }else{
                $codeStatut = "RESERVATION_NOT_EXIST";
            }
        } catch (\Exception $e) {
            $codeStatut = "ERROR";
            $respObjects["err"] = $e->getMessage();
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }

    

}
