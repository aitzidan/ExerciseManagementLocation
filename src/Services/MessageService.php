<?php


namespace App\Services;

use Symfony\Component\PropertyAccess\PropertyAccess;

class MessageService
{
    public function getMessage($CODEERROR)
    {
         $listMessage = array(
            "ERROR-AUTHENTICATION" => "Username ou mot de passe incorrect !",
            "ERROR-NO-ACCOUNT" => "Aucun compte n\'as été trouvé, Voulez-vous créer un compte ?",
            "ERROR-EMPTY-PARAMS" => "Veuillez vérifier vos informations, un des champs est vide !",
            "ERROR" => "Une erreur s'est produite",
            "OK" => "Opération effectuée",
            "NOT_EXIST" => "Cet élément n'existe pas",
            "CAR_NOT_AVAILABLE"=>"Ce véhicule est réservé aux dates sélectionnées",
            "ERROR_DATE" => "Veuillez vérifier la date saisie !",
            "RESERVATION_NOT_EXIST"=>"Ce réservation n'existe pas !",
            "ERROR_AUTHORIZATION_RESERVATION"=>"Vous étes l'autorization de cette ation reservation"
        );
        return $listMessage[$CODEERROR];
    }
}