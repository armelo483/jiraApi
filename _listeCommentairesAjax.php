<?php
session_start();
//header('Content-type: application/json');
require_once 'unirest-php/src/Unirest.php';

// Disables SSL cert validation temporary
Unirest\Request::verifyPeer(false);
Unirest\Request::auth('celine-osac@eurelis.com', '6EEGmTiPv3l7c26cTt7pC29D');

$headers = array(
    'Accept' => 'application/json',
    'X-ExperimentalApi' => 'opt-in' ,
    'Content-Type' => 'application/json'
);

if (isset($_GET['idIssueVal'])) {

    $issueIdCreated = $_GET['idIssueVal'];

    //S'il appuie sur annuler une demande
    if(isset($_GET['annuler'])) {
       //21 est l'id de la transition d'annulation
        $body = <<<REQUESTBODY
{ 
"transition": {
        "id": "21"
    }
}
REQUESTBODY;
        https://eurelis-osac.atlassian.net/rest/api/2/issue/10128/transitions
        $response = Unirest\Request::post(
            'https://eurelis-osac.atlassian.net/rest/api/2/issue/'.$issueIdCreated.'/transitions',
            $headers,
            $body
        );

        echo json_encode([$response->code]);

        exit;
    }

    //On liste tous les commentaires d'un ticket
    $response = Unirest\Request::get(
        'https://eurelis-osac.atlassian.net/rest/servicedeskapi/request/'.$issueIdCreated.'/comment/',
        $headers
    );

    //On recupère tous les fichiers joints d'un ticket
    $responseAttachments = Unirest\Request::get(
        'https://eurelis-osac.atlassian.net/rest/servicedeskapi/request/'.$issueIdCreated.'/attachment',
        $headers
    );
    $attachmentArray = [];

    if(isset($responseAttachments->body->values)) {
        $oneAttachmentArrayTemp = [];
        //$attachmentArray["pieceJointes"] = array_merge([],$responseAttachments->body->values);
        array_walk($responseAttachments->body->values, function($stdObj, $key) use (&$oneAttachmentArrayTemp){
            $oneAttachmentArray = [];
            $oneAttachmentArray['filename'] = (isset($stdObj->filename))?$stdObj->filename:'';
            $oneAttachmentArray['author'] = (isset($stdObj->author->displayName))?$stdObj->author->displayName:'';
            $oneAttachmentArray['thumbnail'] = (isset($stdObj->_links->thumbnail))?$stdObj->_links->thumbnail:'';
            $oneAttachmentArray['mimeType'] = (isset($stdObj->mimeType))?$stdObj->mimeType:'';
            $oneAttachmentArray['content'] = (isset($stdObj->_links->content))?$stdObj->_links->content:'';

            array_push($oneAttachmentArrayTemp,$oneAttachmentArray);
        });

        //$attachmentArray["pieceJointes"] = array_merge([],$responseAttachments->body->values);
        $attachmentArray["pieceJointes"] = array_merge([],$oneAttachmentArrayTemp);
    }


    if(isset($response->body->values) && !empty($response->body->values)) {

        //On retire les notes internes (commentaires privés)
        $response->body->values = array_filter($response->body->values, function($value) {
            return $value->public===true;
        });
        array_walk($response->body->values, function(&$stdObj, $key) {

                if(isset($stdObj->author)  && isset($stdObj->author->displayName)){
                    if(strpos( $stdObj->author->displayName, 'celine') !== false || (strpos($_SESSION['moi'],$stdObj->author->displayName)!== false)) {
                        $stdObj->author->displayName = 'moi';
                    }
                }
        });
        $commentArray = array_values($response->body->values);

        if(!empty($attachmentArray["pieceJointes"]))
            $commentArray = array_merge($commentArray,$attachmentArray);
        //Pour être sur que ce soit un array en paramètre à cause de array_filter
        //echo json_encode($commentArray);
        echo json_encode(array_values($commentArray));
    } elseif(empty($response->body->values)) {
        echo json_encode(['Aucun ']);
    }
} elseif(isset($_POST['commentaire'])) {


    $commentaire = $_POST['commentaire'];
    $issueIdCreated = $_POST['idIssue'];

    include '_addAttachmentFile.php';
    include '_sendCommentWithAttachment.php';


    $body = <<<REQUESTBODY
{
  "public": true,
  "body": "$commentaire"
}
REQUESTBODY;

    $response = Unirest\Request::post(
        'https://eurelis-osac.atlassian.net/rest/servicedeskapi/request/'.$issueIdCreated.'/comment',
        $headers,
        $body
    );
    //retourne le commentaire
    $result = [];
    $result['displayName'] =  (strpos($response->body->author->displayName, 'celine')!==false)? 'moi': $response->body->author->displayName;

    $result['commentaire'] =  $response->body->body;
echo json_encode($result, true);
}

//echo count($response->body->values);
//echo json_encode($issueIdCreated);