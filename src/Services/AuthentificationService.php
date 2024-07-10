<?php


namespace App\Services;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;


class AuthentificationService
{
    private $JWTManager;


    public function __construct(JWTEncoderInterface $JWTManager  ){
        $this->JWTManager = $JWTManager;
    }

    public function checkAuth($codeFunction,$request)
    {
        $jwt = $request->headers->get('Authorization');        
        $isConnected = false;
        $jwt = substr($jwt,7);
        $data = $this->JWTManager->decode($jwt);
        $dataAgent = $data["id"];
        if ($dataAgent) {
            $isConnected = true;
        }
        return $isConnected;
    }
    
    public function getUserId($request)
    {
        $jwt = $request->headers->get('Authorization');        
        if($jwt){
            $jwt = substr($jwt,7);
            $data = $this->JWTManager->decode($jwt);
            $dataUser = $data["id"];
            return $dataUser;
        }else{
            return null;
        }
    }
}