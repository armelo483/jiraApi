<?php
session_start();
require_once 'unirest-php/src/Unirest.php';


// Disables SSL cert validation temporary
Unirest\Request::verifyPeer(false); 
//Unirest\Request::auth('alain-osac@eurelis.com', 'RHRw6XxsBDl9m3MtjjDa42E0');
Unirest\Request::auth('celine-osac@eurelis.com', '6EEGmTiPv3l7c26cTt7pC29D');

//$mailRapporteur= "rdvdir2019@gmail.com";
//exit;
$libelleExtraFieldArr = [
    "customfield_10035" => "Numero de Commande",
    "customfield_10036" => "Numero de Facture",
    "customfield_10037" => "Agrement",
    "customfield_10043" => "Responsable",
    "customfield_10041" => "Type de Service"
];
$ticketTypeArr = [
"15" =>"Nouvelle fonctionnalité",
"14"=>"Signaler un bug",
    "16"=>"Suggérer une amélioration",
    "10"=>"Question sur la facturation et la license",
    "12"=>"Autre question",
    "9"=>"Support technique","11"=>"Question sur la version d'essai d'un produit",
    "13"=>"Requête reçue par mail",
    "22"=> "OSAC"
];
$headers = array(
  'Accept' => 'application/json',
  'X-ExperimentalApi' => 'opt-in' ,
  'Content-Type' => 'application/json'
);
$issueIdOrKey = 10071;
$responseAttachments = Unirest\Request::get(
    'https://eurelis-osac.atlassian.net/rest/servicedeskapi/request/'.$issueIdOrKey.'/attachment',
    $headers
);

$message = "";
$baseUrl = 'https://eurelis-osac.atlassian.net/rest/servicedeskapi/';
$quiSuisJe = '';
//$quiSuisJe = 'test-osac@gmail.com';
$body = '';
$showDiv ='';


//on recupère toutes les files d'attente du service DESK ID "2" (Service En Ligne)

//var_dump($response);exit;


//var_dump($response->body->values[0]->id);exit;
//var_dump($response);exit;
//$listTicketsArrAssoc = [];

/*foreach($listTicketsArr as $stdObject) {
    $listTicketsArrAssoc[$stdObject->reporter->displayName] = $stdObject;
}*/
/*$data = json_encode((array)$response->body);
$datas = json_decode($data);*/


/*$response = Unirest\Request::get(
    'https://eurelis-osac.atlassian.net/rest/servicedeskapi/request/{issueIdOrKey}/comment/{commentId}',
    $headers
);*/


$filteredOwnerTicketArray = [];
function _filterTicketsByOwner ($quiSuisJe,$listTicketsArr, &$filteredOwnerTicketArray){

    //$listTicketsArr = array_merge($listTicketsArr,$response->body->values);
    //var_dump($listTicketsArr); exit;
    array_walk(
        $listTicketsArr,
        function ($stdObj, $key) use (&$filteredOwnerTicketArray, $quiSuisJe) {
            if(isset($stdObj->reporter->displayName)) {
                if(strpos($quiSuisJe,$stdObj->reporter->displayName)!==false){
                    array_push($filteredOwnerTicketArray, $stdObj);
                }
            }
        }
    );
    //var_dump($filteredOwnerTicketArray);exit;
    //return $filteredOwnerTicketArray;
}


//_filterTicketsByOwner($quiSuisJe, $response,$filteredOwnerTicketArray);
//var_dump($filteredOwnerTicketArray);
//exit;

$mailTokenArr = [
        "celine-osac@eurelis.com" => "6EEGmTiPv3l7c26cTt7pC29D",
    'alain-osac@eurelis.com'=> 'RHRw6XxsBDl9m3MtjjDa42E0'
];

if(!empty($_POST)) {
    $body = "";
    $serviceDeskId = 2;
    $response = "";
    $allMyTicketsArr = [];
    if(!empty($_POST['nom'])) {
        $_SESSION['moi'] = $_POST['nom'];
        $moi = preg_split("/[\s,@-]+/", $_SESSION['moi'])[0];
    }

    //var_dump($_POST);exit;
    if(isset($_POST["submitCreateTicket"]) || isset($_POST['isAjax'])) {


        Unirest\Request::auth('celine-osac@eurelis.com', '6EEGmTiPv3l7c26cTt7pC29D');
include '_addAttachmentFile.php';
        //var_dump($_POST);
        //var_dump($_FILES);
        //exit;
        //Pour le moment on crée que sur le serviceDesk "2" si c'est pas une requête ajax
        if(!isset($_POST['isAjax'])) {
            $requestTypeId = $_POST['type-ticket'];
            $summary = $_POST['resume'];
            $description = $_POST['description'];

            $quiSuisJe = $_SESSION['moi'];
            //$quiSuisJe = 'armelo483@yopmail.com';
            //$quiSuisJe = 'alain483@yopmail.com';
            //var_dump($_POST); exit;
//5dd7fbcf03eda50ef3876651 = idUser de Celine
//On crée d'abord le ticket avant de recupérer son id et lui ajouter une pièce jointe (attachment)
            if(empty($_POST['numerodecommande'])) {
                $body = <<<REQUESTBODY
{ "raiseOnBehalfOf": "$quiSuisJe",
  "serviceDeskId": "$serviceDeskId",
  "requestTypeId": "$requestTypeId",
  "requestParticipants": [
    "5dd7fbcf03eda50ef3876651"
   ],
  "requestFieldValues": {
    "summary": "$summary",
    "description": "$description"
  }
}
REQUESTBODY;
            }else {
                $numeroCommande = $_POST['numerodecommande'];
                $numerodeFacture = $_POST['numerodefacture'];
                $terrain = $_POST['terrain'];
                $agrement = $_POST['agrement'];
                //$nomDemandeur = $_POST['nomdemandeur'];
                $typeDeService = $_POST['typedeservice'];
                $responsable = $_POST['responsable'];
                //$dateheure = $_POST['dateheure'];
                $summary = $_POST['resume'];

                $body = <<<REQUESTBODY
{ "raiseOnBehalfOf": "$quiSuisJe",
  "serviceDeskId": "$serviceDeskId",
  "requestTypeId": "$requestTypeId",
  "requestParticipants": [
    "5dd7fbcf03eda50ef3876651"
   ],
  "requestFieldValues": {
    "summary": "$summary",
    "description": "$description",
    "customfield_10035": "$numeroCommande",
    "customfield_10036": "$numerodeFacture",
    "customfield_10037": "$agrement",
    "customfield_10043": "$responsable",
    "customfield_10041": "$typeDeService"
  }
}
REQUESTBODY;

            }



           $response = Unirest\Request::post(
                $baseUrl.'/request',
                $headers,
                $body
            );

            //var_dump($response);exit;
            if(isset($response->body->issueId))
                $issueIdCreated = $response->body->issueId;
        }else {
            //$size = sizeof($temporaryAttachmentIdsArr);
            $empty = empty($_FILES);
            //echo "size = ".$size;
            echo "fileEmpty = ".$empty;

        }


include '_sendCommentWithAttachment.php';

        $success = ($response->code>=200 && $response->code<300)?:0;
        $moi = $_SESSION['moi'];
        if($success) {
            $moi = ucfirst(preg_split("/[\s,@-]+/", $_SESSION['moi'])[0]);
            $message = $moi.", nous avons bien reçu votre demande et sommes dessus!";
        }else {
            $message = "Echec lors de la création du ticket";
        }

    } elseif (isset($_POST['submitCreateCustomer'])) {

        $displayName = $_POST['displayName'];
        $email = $_POST['email'];

        //Création
        $body = <<<REQUESTBODY
{
  "displayName": "$displayName",
  "email": "$email"
}
REQUESTBODY;

        $response = Unirest\Request::post(
            $baseUrl.'customer',
            $headers,
            $body
        );
        $success = ($response->code>=200 && $response->code<300)?:0;
        if($success) {
            $message = "compte créé avec succès !";
        }else {
            $message = "Echec lors de la création du compte";
        }

    }

}


//On recupère la liste de tous les tickets
if(!empty($_SESSION["moi"])){

    if(!empty($_POST["nom"])) {
        $nom = trim($_POST["nom"]);
        $_SESSION['moi'] = $nom;
    }else {
        $nom = $_SESSION['moi'];
    }

    $quiSuisJe = $nom;
    //Unirest\Request::auth($nom, $mailTokenArr[$nom]);
    //Unirest\Request::auth($nom, $mailTokenArr[$nom]);

    $response = Unirest\Request::get(
        $baseUrl.'/request',
        $headers,
        $body
    );

    $showDiv = ($response->code>=200 && $response->code<300)?:0;
    $allMyTicketsArr = array_merge($allMyTicketsArr,$response->body->values);
    _filterTicketsByOwner($quiSuisJe, $allMyTicketsArr,$filteredOwnerTicketArray);
    //$filteredOwnerTicketArray = array_merge($filteredOwnerTicketArray,$allMyTicketsArr);
    //var_dump($filteredOwnerTicketArray);
    //var_dump($allMyTicketsArr);
    //exit;
    // Unirest\Request::auth('celine-osac@eurelis.com', '6EEGmTiPv3l7c26cTt7pC29D');
}

/*
$response = Unirest\Request::get(
  'https://eurelis-osac.atlassian.net/rest/servicedeskapi/servicedesk/'.$serviceDeskId.'/customer',
  $headers
);

var_dump(get_class_methods('Unirest\Request')); exit;
$myObj = $response->getBody()->getObject();
$test = $myObj->getJSONArray();var_dump($test); exit;*/

?>


<html>
<head>
<title>Jira API Testing</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Solway&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" rel="stylesheet">
</head>

<body>


<?php if(isset($success) && !empty($message) &&(!$success)){ ?>
<div style="position: relative;z-index: 7000;" class="alert alert-dismissible fade show  alert-danger" role="alert">
    <?php echo $message; ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" >
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<?php } ?>
<div class="container">
    <!-- Qui suis je -->
    <div class="row">
        <div class="col-md-12 identite mb-3" style ="background: lemonchiffon;height:124px;">
            <legend>
                <?php if(!isset($_SESSION['moi'])) {
                echo "Qui suis je ?";
                }else {
                    $moi = ucfirst(preg_split("/[\s,@-]+/", $_SESSION['moi'])[0]);
                    echo "Bonjour ".$moi;
                } ?>
            </legend>
            <!-- Select Basic -->
            <form class="form-inline" id="loginForm" name="loginForm" method="post">
                <div class="col-md-4 form-group">
                    <label class="col-md-3 control-label" for="nom">Mail </label>
                    <input id="nom" name="nom" type="text" placeholder="Votre mail" class="form-control input-md nom col-md-9">
                </div>
                <button id="identifie" name="submitIdentifie" class="form-control btn btn-success" type="submit" style="font-size:1em;">Je m'identifie</button>
            </form>
                <a href="javascript:void(0)" id="inscription" style="font-size:0.9em;position: absolute;bottom:2px;left: 17px;">Nouveau? Je m'inscris!</a>
        </div>
    </div>
    <!-- Créer un projet -->
   <!-- <div class="row animated rotateInUpleft">
        <button class="btn btn-success create-project mt-8" style="z-index: 5000;width:126px; height: 400px;">Créer un projet</button>
        <?php //include 'form/_createProject.php' ?>
    </div> -->

    <!-- Créer et/ou consulter un ou des ticket(s) -->
        <div class="row animated <?php if(!isset($_POST['submitCreateTicket'])) { ?>fadeInLeft create-ticket-row<?php } ?>"  <?php if(!isset($_SESSION['moi'])) {?>d-none <?php } ?> >
            <button class="btn btn-success create-ticket mt-8" style="z-index: 5000;width:126px; height: 470px;margin-top: 18px;">Créer un ticket</button>
            <?php include 'form/_createTicket.php' ?>
        </div>

    <div class="row  <?php if(!isset($_SESSION['moi'])) {?>d-none <?php } ?> liste-tickets-row">
        <button class="btn btn-success liste-tickets mt-8" style="z-index: 5000;width:126px; height: 470px;margin-top: 18px;">Consulter l'état de mes tickets </button>
        <?php include 'form/_listeMesTickets.php' ?>
    </div>
    <!-- Créer et/ou consulter un ou des ticket(s) -->

     <div class="row animated fadeInLeft d-none create-compte-row-div">
        <button class="btn btn-success create-compte mt-8" style="z-index: 5000;width:126px; height: 400px;">Créer un compte client </button>
        <?php include 'form/_createCustomer.php' ?>
    </div>

</div>
<!-- loaders -->
<div class="loader" id="loader"><img  src="plane-loading.gif"/></div>
<style>

    .parent-liste {
    z-index:-45;
    }
    #loader {
        //background: url(avion.gif);
        background: white;
        //background-repeat: no-repeat;
        opacity: 0.71;
        display: none;
        position: absolute;
        text-align:center;
        padding-top: 477px;
        left: 0;
        right: 0;
        top: 177px;
        height: 7300px;
        width: 100%;
        background-size: contain;
        z-index: 9999999;
    }
    .conteneur, hr {
        background: darkgray;
        margin: 0 0 0 12px;
    }

    .conteneur {
        color: white;
    }
    .btn-success {
        font-size: 1.2em;
        font-family: 'Solway', serif;
    }
    .fade-in::before {
        //position:absolute;
        width: 100px;
        height: 100%;
        background: aquamarine;
        //border: 1px solid red;
        //z-index: 9999;

    }
    .create-form-div-extra {
        opacity: 0;
        background: lemonchiffon;
        z-index: 0;
        position: absolute;
        top:0;
        right: 340px;
        width: 400px;
        transition: all 1.6s ease-out;
        transform: translateX(-7%);

    }
    .create-form-div-extra.show-extra {
        opacity: 1;
        z-index: 9999;
        //z-index: 0;
        transform: translateX(100%);
    }
    .fade-in {
        opacity: 0;
        z-index: -100;
        //transform: translateX(-100%);
        transform: rotateY(-180deg);;
        transition: all .75s ease-in;
    }

    .fade-in.show {
        z-index: 999;
        opacity: 1;
    //transform: translateX(0);
        transform: rotateY(0deg);
    }
#accordion .collapse-tr {
    //display: none;
}



    *{
        font-family: 'Leckerli One', cursive;
        font-size: 1em;
    }
    .create-form-div form{
        padding-left: 10px;
    }

    .create-form-div{
        background: lemonchiffon;
        opacity: 0;
    }

    .annulee-div {
        background: grey;
        height: 27px;
        padding: 2px 0 0 30px;
        color: white;
    }
    /* Animation code bienvenue */

    .splashscreen {
        position: absolute;
        top:0;
        left:0;
        height:1440px;
        width:100%;
        background: #192018;
        color: white;
        z-index: 7000;
        animation-delay: 14s;
        text-align:center;
        font-weight:300;
        font-size:32px;
        padding-top:20vh;
        overflow:hidden;
        //-webkit-backface-visibility: hidden;
        //-webkit-perspective: 1000;
        //-webkit-transform: translate3d(0,0,0);

    }

    .splashscreen div {
        display:inline-block;
        overflow:hidden;
        white-space:nowrap;
    }

    .splashscreen div:first-of-type {
        animation: showup 21s forwards;
    }

    .splashscreen div.bonne-annee {
        width:0px;
        animation: reveal 21s forwards;
    }


    .splash-content{
        padding-right: 7px !important;
    }

    .splashscreen div.bonne-annee {
        margin-right:-380px;
        margin-top: 0px;
    //animation: slidein 14s forwards;
        width: 370px;
    //height: 40px;
    //width: 100px;
    //background: red;
    }

    .exclamation {
        opacity: 0;
        animation: showup 21s forwards 7s;
    }

    @keyframes showup {
        0% {opacity:0;}
        20% {opacity:0.2;}
        80% {opacity:0.34;}
        100% {opacity:0.34;}
    }
    .text-muted {
        color: #ece617 !important;
    }
    @keyframes slidein {
        0% { margin-left:100px; opacity:0; }
        60% { margin-left:52px; opacity:0.1; }
        89% { margin-left:22px; opacity:0.33;}
        100% { margin-left:1px; opacity:0.34;}
    }

    @keyframes reveal {
        0% {opacity:0;width:0px;}
        20% {opacity:0.2;width:0px;}
        25% {opacity:0.2;width:0px;}
        80% {width:580px;}
        80% {opacity:0.34;}
        100% {opacity:0.34;width:580px;}
    }

    .create-ticket-row {
        animation-delay: 15s !important;
    }
    /* css splashscreen */


    .christmas-hat {
        background-image: url(https://static-seloger.com/commons/header/hat.svg);
        height: 49px;
        position: absolute;
        left: -28px;
        top: -13px;
        width: 47px;
        z-index: 7779;
    }

    /* Animation Confetti */

    [class|="confetti"] {
        position: absolute;
    }

    .red {
        background-color: #E94A3F;
    }

    .yellow {
        background-color: #FAA040;
    }

    .blue {
        background-color: #5FC9F5;
    }
    /* Fin Animation Confetti */

</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="http://bootstrap-notify.remabledesigns.com/js/bootstrap-notify.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script  type="text/javascript">

    $(document).ready(function(){

        $('#selectbasic').change(function(){
            var valueOptionSelected = $("#selectbasic option:selected").val();

            if(valueOptionSelected === '22') {
                $('.create-form-div-extra').addClass('show-extra');

            }else {
                $('.create-form-div-extra').removeClass('show-extra');
            }
        });
        //$('#accordion').DataTable();
        <?php if(isset($success) && !empty($message) &&($success)){ ?>
            $.notify("<?php echo $message ?>", {
                animate: {
                    enter: 'animated fadeInRight',
                    exit: 'animated fadeOutRight'
                }
            });
        <?php } ?>

        if(localStorage.getItem('idForms')===null){
            console.log('rien dans le dom');
        }else {

            var jsonObj = JSON.parse(localStorage.getItem('idForms'));
            var $form = $("#"+jsonObj['idForm']);

            if(jsonObj['idForm']!='customerForm') {
                $form.find('.description').val(jsonObj['description']);
                $form.find('.resume').val(jsonObj['resume']);
            }else {
                $form.find('.nom').val(jsonObj['nom']);
                $form.find('.email').val(jsonObj['email']);
            }

        }

        $( "#accordion" ).on( "click", ".afficher-detail-ticket", function(event) {
            var idCollapsibleItem = $(this).attr('href');
            var $collapsibleTr = $(idCollapsibleItem).parent().parent();
            $collapsibleTr.toggleClass('show');
            console.log($collapsibleTr);
            //$(idCollapsibleItem).parent().parent().show();
        });

        //Ajouter un commentaire en Ajax
        $( "#fullHeightModalRight" ).on( "submit", "#commentaireForm", function(event) {

            event.preventDefault();
            var commentaire = $('#commentaire').val();
            var idIssue = $('#idIssue').val();
            var url = '_listeCommentairesAjax.php';
            var formData = {
                'commentaire': commentaire,
                'addCommentaire': true,
                'idIssue': idIssue,
                'isAjax' : true
            };

            if( document.getElementById("attachmentFileUpload").files.length > 0 ){
                //url = "index.php";
                formData = $(this).serialize();
            }
           formData = new FormData(this);

            $('.loader').show();
            console.log(url);
            console.log('formData');
            console.log(formData);
            $.ajax({
                type: 'POST',
                url: url,
                data : formData,
                cache: false,
                contentType: false,
                processData: false,
                //async: false,
                //contentType: 'json',
                //processData : false,
                //dataType : 'json',
                success: function( resp ) {
                    var addedHtmlComment = '';
                    var pieceJointeSTring = '';
                    console.log(resp);
                    var parsedResp = JSON.parse(resp);
                    $('#loader').hide();

                        addedHtmlComment ="<div class='conteneur animated bounceIn' style='display:flex;'>\n" +
                            "                        <div>\n" +
                            "                        <img class='rounded-circle' src='//a.disquscdn.com/1574374277/images/noavatar92.png'>\n" +
                            "                        </div>\n" +
                            "\n" +
                            "                        <div style='width:500px;margin-left: 27px;overflow-wrap: break-word;'>\n" +
                            "                        <span style='font-size: 1.3em;'><b>"+ parsedResp["displayName"]+"</b></span><p>"+parsedResp["commentaire"]+" </p>\n" +
                            "                    </div>\n" +
                            "</div>\n" +
                            "                    </div><hr style='border: 1px solid #ccc;'/><br>";



                    $( addedHtmlComment ).insertBefore( "#commentaireForm" );
                    $('#commentaire').val('');
                    $('#attachmentFileUpload').val('');
                    //setTimeout(function(){ $('#fullHeightModalRight').modal('hide'); }, 3000);

                },
                error : function(resultat, statut, erreur){
                    console.log(erreur);
                    console.log(resultat);
                    $('#loader').hide();
                    alert('oops');
                },
                beforeSend: function(xhr) {
                    /*if (xhr && xhr.overrideMimeType) {
                        xhr.overrideMimeType("application/j-son;charset=UTF-8");
                    }*/
                    // xhr.setRequestHeader("Authorization", "Basic " +btoa('celine-osac@eurelis.com:6EEGmTiPv3l7c26cTt7pC29D'));
                    //xhr.setRequestHeader("Access-Control-Allow-Headers", "x-requested-with");
                    //xhr.setRequestHeader("X-ExperimentalApi", "opt-in");
                    //xhr.setRequestHeader("Accept", "application/json");
                    //xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                    //console.log(xhr);
                }
            });

        });

        //Annule la demande
        $(".annuler-demande").click(function(){

            var currentId = $(this).attr('id');
            var idIssueVal = currentId.substring(8);
            var url = '_listeCommentairesAjax.php';
            var type = 'GET';
            var dataToSend = {isAjax : 'true', idIssueVal : idIssueVal, annuler: true};
            console.log(idIssueVal);
            $('#loader').show();
            $.ajax({
                type: type,
                url: url,
                data : dataToSend,
                //dataType : 'json',
                success: function( resp ) {
                    $( "#"+currentId ).replaceWith("<div class='annulee-div animated fadeInLeft'>Demande annulée</div>");
                    $( "#status_"+idIssueVal ).html("Annulé");
                    $('#loader').hide()
                    $.notify("Vous n'avez plus de tunes pour cette demande &#128549; ? OK elle est annulée &#x1F62D; &#x1F62D;!", {
                        animate: {
                            enter: 'animated fadeInRight',
                            exit: 'animated fadeOutRight'
                        }
                    });


                    setTimeout(function() { $.notify("Je peux vous prêter de l'argent si vous voulez &#x1F62D; &#x1F62D;!", {
                        animate: {
                            enter: 'animated fadeInRight',
                            exit: 'animated fadeOutRight'
                        }
                    }); }, 4000);

                    /*$.notify("Demande annulée avec succès :( !", {
                        animate: {
                            enter: 'animated fadeInRight',
                            exit: 'animated fadeOutRight'
                        }
                    });*/
                },
                error : function(resultat, statut, erreur){
                    $('#loader').hide();
                    alert('oops');
                },
                beforeSend: function(xhr) {
                }
            });

        });

        //Liste tous les commentaires au click
        $(".afficher-comment").click(function(){

            var idIssueVal = $(this).next().val();
            var url = '_listeCommentairesAjax.php';
            var type = 'GET';
            var dataToSend = {isAjax : 'true', idIssueVal : idIssueVal};
            console.log(idIssueVal);
            $('#loader').show();
            $.ajax({
                type: type,
                url: url,
                data : dataToSend,
                //dataType : 'json',
                success: function( resp ) {
                    console.log(resp);
                    var parsedResp = JSON.parse(resp);
                    console.log(parsedResp);
                    //console.log(parsedResp.length);
                    //var parsedResp = JSON.parse(resp);
                    var contentString ="";
                    var attachmentArray = [];
                    if(Array.isArray(parsedResp[parsedResp.length-1])) {
                        attachmentArray = parsedResp.pop();
                    }
                    //
                    console.log(attachmentArray);

                    for(i=0;i<parsedResp.length;i++) {

                        if(parsedResp[i]["author"] !== undefined && parsedResp[i]["body"]!== undefined){

                            contentString +="<div class='conteneur animated fadeIn' style='display:flex;'>\n" +
                                "                        <div>\n" +
                                "                        <img class='rounded-circle' src='//a.disquscdn.com/1574374277/images/noavatar92.png'>\n" +
                                "                        </div>\n" +
                                "\n" +
                                "                        <div style='width:500px;margin-left: 27px;overflow-wrap: break-word;'>\n" +
                                "                        <span style='font-size: 1.3em;'><b>"+ parsedResp[i]["author"]["displayName"]+"</b></span><p>"+parsedResp[i]["body"]+'</p>\n' +
                                "                    </div>\n" +
                                "                    </div><hr style='border: 1px solid #ccc;'/><br>";
                        }

                    }

                    contentString+= '<form action="_listeCommentairesAjax.php" class="form-horizontal" name="commentaireForm" id="commentaireForm" method="post" enctype="multipart/form-data"><div class="form-group">\n' +
                        '                           <label class="col-md-3 control-label animated fadeInLeft" for="textarea">Commentaire</label>\n' +
                        '                           <div class="col-md-12 animated fadeInLeft">\n' +
                        '                               <textarea class="form-control commentaire" id="commentaire" name="commentaire" placeholder="Ajouter un commentaire"></textarea>\n' +
                        '<div class="form-group mt-2">'+
                        '<input type="file" class="form-control-file" name="pieceJointes[]" id="attachmentFileUpload" multiple>'+
                        '</div>'+
                        '<input type="submit" class="btn btn-primary pull-right" value="Je commente"/>'+
                        ' </div>\n' +
                    '</div><input type="hidden" name="isAjax" id="isAjax" value="true"><input type="hidden" name="idIssue" id="idIssue" value='+idIssueVal+'></form>';
                    $('#modalBody').html(contentString);
                    $('#loader').hide();
                    //console.log(resp);
                    $('#fullHeightModalRight').modal('show');
                },
                error : function(resultat, statut, erreur){
                    console.log(erreur);
                    console.log(resultat);
                    $('#loader').hide();
                    alert('oops');
                },
                beforeSend: function(xhr) {
                    /*if (xhr && xhr.overrideMimeType) {
                        xhr.overrideMimeType("application/j-son;charset=UTF-8");
                    }*/
                    // xhr.setRequestHeader("Authorization", "Basic " +btoa('celine-osac@eurelis.com:6EEGmTiPv3l7c26cTt7pC29D'));
                    //xhr.setRequestHeader("Access-Control-Allow-Headers", "x-requested-with");
                    //xhr.setRequestHeader("X-ExperimentalApi", "opt-in");
                    //xhr.setRequestHeader("Accept", "application/json");
                    //xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
                    //console.log(xhr);
                }
            });
        });


        $('form').submit(function( event ) {

            var resume = '';
            var nom = '';
            var email = '';
            var description = '';
            var idForm = $(this).attr('id');
            var localStorObj = '';

            if($(this).attr('id') == 'idForm' ) {
                resume = $(this).find('.resume').val();
                description = $(this).find('.description').val();
                localStorObj = {resume: resume, description:description,idForm:idForm};
            }else {
                nom = $(this).find('.nom').val();
                email = $(this).find('.email').val();
                localStorObj = {nom: nom, email:email,idForm:idForm};
            }

            localStorage.setItem('idForms', JSON.stringify(localStorObj));

        });

        $("#inscription").click(function(){
            $('.create-compte-row-div').removeClass('d-none');
        });

        /*$("button[type='submit']").click(function(){
            $('#loader').show();
        });*/


        $(".liste-tickets").click(function(){

            $('.liste-tickets-div').toggleClass(function() {

                if ( $(this).hasClass('show') ) {
                    $(this).parent().parent().find('button.liste-tickets').html('Consulter l\'état de mes tickets');
                    $(this).parent().css('z-index','-45');
                    //$('#overlayCustomer').show();
                    $(this).removeClass('show');

                    return "";

                } else {
                    $(this).parent().css('z-index','100');
                    $(this).parent().parent().find('button.liste-tickets').html('Annulez');
                    //$('#overlayCustomer').hide();
                    $(this).addClass('show');

                    return "";

                }
            });

        });
        //$('.loader').show();

        $("#identifie").click(function(){
            $('.loader').css('top', '-207px');
            $('.loader').show();
        });
        $("#createTicket").click(function(){
            $('.loader').css('top', '-191px');
            $('.loader').show();
        });
        $(".create-compte").click(function(){
            $otherDiv = $(this).parent().parent().find('.create-ticket-div');

            if($otherDiv.hasClass('show')) {
                $('#overlayTicket').show();
                $otherDiv.removeClass('show');
                $otherDiv.parent().parent().find('button.create-ticket').html('Créez un ticket');
            }

            $('.create-customer-div').toggleClass(function() {

                if ( $(this).hasClass('show') ) {
                    $(this).parent().parent().find('button.create-compte').html('Créez un compte client');
                    $('#overlayCustomer').show();
                    $(this).removeClass('show');

                    return "";

                } else {
                    $(this).parent().parent().find('button.create-compte').html('Annulez');
                    $('#overlayCustomer').hide();
                    $(this).addClass('show');

                    return "";

                }
            });
        });

        $(".create-ticket").click(function(){
            $otherDiv = $(this).parent().parent().find('.create-customer-div');
            if($otherDiv.hasClass('show')) {
                $otherDiv.removeClass('show');
                $('#overlayCustomer').show();
                $otherDiv.parent().parent().find('button.create-compte').html('Créez un compte client');
            }
            //$('.create-ticket-div').toggleClass('show');
            //$('#fade-in').toggleClass('show');
            $('.create-ticket-div').toggleClass(function() {
                if ( $(this).hasClass('show') ) {
                    $('#overlayTicket').show();
                    $(this).parent().parent().find('button.create-ticket').html('Créez un ticket');
                    $(this).removeClass('show');

                    return "";

                } else {
                    $('#overlayTicket').hide();
                    $(this).parent().parent().find('button.create-ticket').html('Annulez');
                    $(this).addClass('show');

                    return "";

                }
            });

        });

        /* JS ANIMATION SNOW PAPER */
        for (var i = 0; i < 290; i++) {
            create(i);
        }
    });



    function create(i) {
        var width = Math.random() * 12;
        var height = width * 0.6;
        var colourIdx = Math.ceil(Math.random() * 3);
        var colour = "red";
        switch(colourIdx) {
            case 1:
                colour = "yellow";
                break;
            case 2:
                colour = "blue";
                break;
            default:
                colour = "red";
        }
        $('<div class="confetti-'+i+' '+colour+'"></div>').css({
            "width" : width+"px",
            "height" : height+"px",
            "top" : -Math.random()*20+"%",
            "left" : Math.random()*100+"%",
            "opacity" : Math.random()+0.5,
            "transform" : "rotate("+Math.random()*360+"deg)"
        }).appendTo('.splashscreen');

        drop(i);
    }

    function drop(x) {
        $('.confetti-'+x).animate({
            top: "100%",
            left: "+="+Math.random()*15+"%"
        }, Math.random()*3000 + 3000, function() {
            reset(x);
        });
    }

    function reset(x) {
        $('.confetti-'+x).animate({
            "top" : -Math.random()*20+"%",
            "left" : "-="+Math.random()*15+"%"
        }, 0, function() {
            drop(x);
        });
    }

    /* */
</script>


<!-- Bienvenue Animation -->
<?php if (isset($_POST["nom"])) {?>
    <div class="splashscreen animated slideOutLeft" style="/*background: #192018;*/ background: whitesmoke; color: black; display: flex;">

        <div class="splashscreen-fix" style="position: relative;top: 87px;opacity:0.34;<?php

        if(strlen($moi)>7) {?> left: 609px; <?php }else {?> left: 527px; <?php } ?>">
            <span> <?php
                echo ucfirst($moi);
                ?>  </span>
            <span class="exclamation"> ! </span>
        </div>

        <div class="splashscreen-div bonne-annee" style="position: relative;top: 88px;opacity:0.34;">
            Bonne année 2020
        </div>


    </div>
<?php } ?>

<!-- -->
</body>

</html>