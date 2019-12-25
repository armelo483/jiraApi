<?php

$temporaryAttachmentIdsArr = [];

if(!empty($_FILES)) {
    //$url = $baseUrl.'servicedesk/'.$serviceDeskId.'/attachTemporaryFile';
    //$filesArr = [];
    $countfiles = count($_FILES['pieceJointes']['name']);
    $baseFileOptionDeb = '-F "file=@';
    $filename = '';
    for($i=0;$i<$countfiles;$i++){
        $otherFilename = $_FILES['pieceJointes']['name'][$i];
        move_uploaded_file($_FILES['pieceJointes']['tmp_name'][$i],$otherFilename);
        $filename.=$baseFileOptionDeb.$otherFilename.'" ';
    }

    //On envoie les pièces jointes dans le Jira Desk
    $results = exec('curl -D-  -u celine-osac@eurelis.com:6EEGmTiPv3l7c26cTt7pC29D -H "X-ExperimentalApi: opt-in" -H "X-Atlassian-Token: no-check" '.$filename.' -X POST https://eurelis-osac.atlassian.net/rest/servicedeskapi/servicedesk/2/attachTemporaryFile');
    $resultsArr = json_decode($results, true);

    if(!empty($resultsArr)) {
        foreach($resultsArr["temporaryAttachments"] as $item){
            array_push($temporaryAttachmentIdsArr, $item["temporaryAttachmentId"]);
        }
    }

    //var_dump($resultsArr["temporaryAttachments"]);
    //var_dump($temporaryAttachmentIdsArr);exit;

}
