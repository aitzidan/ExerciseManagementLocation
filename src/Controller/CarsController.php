<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class CarsController extends AbstractController
{
    private $carsRepo ;
    private $MessageService;

    public function __construct(
        CarRepository $carsRepo , 
        MessageService $MessageService , 
    ){
        $this->carsRepo = $carsRepo;
        $this->MessageService = $MessageService;
    }

    #[Route('/cars',name:"get_cars_available")]
    public function getCarsAvailable(): JsonResponse
    {
        $respObjects =array();
        $codeStatut = "ERROR";
        try{

            $data = $this->carsRepo->getCarsAvailable();
            $codeStatut = "OK";
            $respObjects["data"] = $data;

        }catch(\Exception $e){
            $codeStatut = "ERROR";
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }
    #[Route('/cars/{id}', name:"get_specify_cars")]
    public function getDetailsCars(EntityManagerInterface $em , $id): JsonResponse
    {
        $respObjects =array();
        $codeStatut = "ERROR";
        try{

            $Car = $this->carsRepo->getOneCar($id);
            if($Car){
                $data = $Car;
                $codeStatut = "OK";
                $respObjects["data"] = $data;
            }else{
                $codeStatut = "NOT_EXIST";
            }

        }catch(\Exception $e){
            $codeStatut = "ERROR";
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }
}
