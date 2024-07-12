<?php

namespace App\Controller;

use App\Entity\Car;
use App\Repository\CarRepository;
use App\Services\CarService;
use App\Services\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

#[Route('/api')]
class CarsController extends AbstractController
{
    private $carService;
    private $messageService;
    private $jwtManager;

    public function __construct(
        CarService $carService,
        MessageService $messageService,
        JWTEncoderInterface $jwtManager
    ) {
        $this->carService = $carService;
        $this->messageService = $messageService;
        $this->jwtManager = $jwtManager;
    }

    #[Route('/cars', name: "get_cars_available")]
    public function getCarsAvailable(Request $request): JsonResponse
    {
        //TODO:Récupération véhicule disponible

        $respObjects = [];
        $codeStatut = "ERROR";
        try {
            $data = $this->carService->getCarsAvailable();
            $codeStatut = "OK";
            $respObjects["data"] = $data;
        } catch (\Exception $e) {
            $codeStatut = "ERROR";
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->messageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }

    #[Route('/cars/{id}', name: "get_specify_cars")]
    public function getDetailsCars($id): JsonResponse
    {
        //TODO:Récupération véhicule détails
        $respObjects = [];
        $codeStatut = "ERROR";
        try {
            $car = $this->carService->getOneCar($id);
            if ($car) {
                $data = $car;
                $codeStatut = "OK";
                $respObjects["data"] = $data;
            } else {
                $codeStatut = "NOT_EXIST";
            }
        } catch (\Exception $e) {
            $codeStatut = "ERROR";
        }
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->messageService->getMessage($codeStatut);
        return $this->json($respObjects);
    }
}
