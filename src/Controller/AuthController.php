<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\MessageService;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api'  ) ]
class AuthController extends AbstractController
{
    private $JWTManager;
    private $MessageService;
    private $userRepository;

    public function __construct(
        MessageService $MessageService , 
        JWTEncoderInterface $JWTManager ,
        UserRepository $userRepository
        )
    {
        $this->MessageService = $MessageService;
        $this->JWTManager = $JWTManager;
        $this->userRepository = $userRepository;
    }
    
    #[Route('/authentification', name: 'authentification' , methods:["POST"])]
    public function index(Request $request): JsonResponse
    {
        $codeStatut="ERROR";
        $respObjects=array();
        try {
            $email = $request->get("email");
            $password = $request->get("password");
            if (!empty($email) && !empty($password)) {
                $findUser = $this->userRepository->findUserByEmailAndPass($email , $password);

                if($findUser){
                    $data = array("id"=>$findUser->getId());
                    $tokenJWT = $this->JWTManager->encode($data);
                    $respObjects["jwt"] = $tokenJWT;
                    
                    $codeStatut="OK";
                }else{
                    $codeStatut="ERROR-AUTHENTICATION";
                }
            }else{
                $codeStatut="EMPTY-DATA";
            }
        } catch (\Exception $e) {
            $codeStatut="ERROR";
        }
        
        $respObjects["codeStatut"] = $codeStatut;
        $respObjects["message"] = $this->MessageService->getMessage($codeStatut);

        return $this->json($respObjects);
    }
}
