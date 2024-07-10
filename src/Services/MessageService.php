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
        );
        return $listMessage[$CODEERROR];
    }
}