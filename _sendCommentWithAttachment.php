<?php
if(!empty($temporaryAttachmentIdsArr)) {

    $baseUrl = 'https://eurelis-osac.atlassian.net/rest/servicedeskapi/';
    $comment = (isset($_POST['isAjax']))?$_POST['commentaire']."<br><u style='color: blueviolet'>Pièces jointes</u><br>": null;
    $temporaryAttachmentIdsString = implode('","', $temporaryAttachmentIdsArr);

    //On adjoint les pièces jointes du Jira Desk au ticket
    $body = <<<REQUESTBODY
{
"temporaryAttachmentIds": ["$temporaryAttachmentIdsString"],
 "public": true,
 "additionalComment": {
    "body": "$comment"
  }
}
REQUESTBODY;

    //On cree le commentaire du ticket avec la pièce jointe
    $response = Unirest\Request::post(
        $baseUrl.'/request/'.$issueIdCreated.'/attachment',
        $headers,
        $body
    );


    $result['displayName'] =  (strpos($response->body->attachments->values[0]->author->displayName, 'celine')!==false)? 'moi': $response->body->attachments->values[0]->author->displayName;
    $result['commentaire'] =  $response->body->comment->body;
    $result['pieceJointe'] = [];
    foreach($response->body->attachments->values as $key=>$val) {
        $result['pieceJointe'][] = $val->filename;
    }

    if(isset($_POST['isAjax'])) {
        echo json_encode($result);
        exit;
    }

}