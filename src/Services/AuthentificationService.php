<?php


namespace App\Services;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;


class AuthentificationService
{
    private $JWTManager;


    public function __construct(JWTEncoderInterface $JWTManager  ){
        $this->JWTManager = $JWTManager;
    }

    public function getUserId($request)
    {
        $jwt = $request->headers->get('Authorization');        
        $jwt = substr($jwt,7);
        $data = $this->JWTManager->decode($jwt);
        $dataUser = $data["id"];
        return $dataUser;
    }
}