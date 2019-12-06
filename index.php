<?php
require_once 'unirest-php/src/Unirest.php';


// Disables SSL cert validation temporary
Unirest\Request::verifyPeer(false); 
//Unirest\Request::auth('alain-osac@eurelis.com', 'RHRw6XxsBDl9m3MtjjDa42E0');
Unirest\Request::auth('celine-osac@eurelis.com', '6EEGmTiPv3l7c26cTt7pC29D');

//$mailRapporteur= "rdvdir2019@gmail.com";
//exit;
$ticketTypeArr = [
"15" =>"Nouvelle fonctionnalité",
"14"=>"Signaler un bug",
    "16"=>"Suggérer une amélioration",
    "10"=>"Question sur la facturation et la license",
    "12"=>"Autre question",
    "9"=>"Support technique","11"=>"Question sur la version d'essai d'un produit",
    "13"=>"Requête reçue par mail"
];
$headers = array(
  'Accept' => 'application/json',
  'X-ExperimentalApi' => 'opt-in' ,
  'Content-Type' => 'application/json'
);
$message = "";
$baseUrl = 'https://eurelis-osac.atlassian.net/rest/servicedeskapi/';

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

$filteredOwnerTicketArray = [];
function _filterTicketsByOwner ($quiSuisJe,$listTicketsArr, &$filteredOwnerTicketArray){

    //$listTicketsArr = array_merge($listTicketsArr,$response->body->values);
    //var_dump($listTicketsArr); exit;
    array_walk(
        $listTicketsArr,
        function ($stdObj, $key) use (&$filteredOwnerTicketArray, $quiSuisJe) {
            if(isset($stdObj->reporter->emailAddress)) {
                if($quiSuisJe == $stdObj->reporter->emailAddress){
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

    if(!empty($_POST["nom"])){
        $nom = trim($_POST["nom"]);
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
    }elseif(isset($_POST["submitCreateTicket"])) {
        Unirest\Request::auth('celine-osac@eurelis.com', '6EEGmTiPv3l7c26cTt7pC29D');
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


            $results = exec('curl -D-  -u alain-osac@eurelis.com:RHRw6XxsBDl9m3MtjjDa42E0 -H "X-ExperimentalApi: opt-in" -H "X-Atlassian-Token: no-check" '.$filename.' -X POST https://eurelis-osac.atlassian.net/rest/servicedeskapi/servicedesk/2/attachTemporaryFile');
           $resultsArr = json_decode($results, true);

           if(!empty($resultsArr)) {
               foreach($resultsArr["temporaryAttachments"] as $item){
                   array_push($temporaryAttachmentIdsArr, $item["temporaryAttachmentId"]);
               }
           }

            //var_dump($resultsArr["temporaryAttachments"]);
           //var_dump($temporaryAttachmentIdsArr);exit;

        }
        //var_dump($_POST);
        //var_dump($_FILES);
        //exit;
        //Pour le moment on crée que sur le serviceDesk "2"

        $requestTypeId = $_POST['type-ticket'];
        $summary = $_POST['resume'];
        $description = $_POST['description'];

        //$quiSuisJe = $mailRapporteur;

//On crée d'abord le ticket avant de recupérer son id et lui ajouter une pièce jointe (attachment)
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


        $response = Unirest\Request::post(
            $baseUrl.'/request',
            $headers,
            $body
        );
//var_dump($response);exit;
if(isset($response->body->issueId))
 $issueIdCreated = $response->body->issueId;
if(!empty($temporaryAttachmentIdsArr)) {


    $temporaryAttachmentIdsString = implode('","', $temporaryAttachmentIdsArr);

    //Création de la requête avec fichier joint (attachments)
    $body = <<<REQUESTBODY
{
"temporaryAttachmentIds": ["$temporaryAttachmentIdsString"],
 "public": true
}
REQUESTBODY;

    $response = Unirest\Request::post(
        $baseUrl.'/request/'.$issueIdCreated.'/attachment',
        $headers,
        $body
    );
}


        $success = ($response->code>=200 && $response->code<300)?:0;

        if($success) {
            $message = "Ticket créé avec succès !";
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
    <link href="https://fonts.googleapis.com/css?family=Solway&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@atlaskit/css-reset@2.0.0/dist/bundle.css" media="all">
    <script src="https://connect-cdn.atl-paas.net/all.js" async></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-notify/0.2.0/css/bootstrap-notify.min.css" rel="stylesheet">
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
            <legend> Qui suis je ?</legend>
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
    <div class="row animated fadeInLeft <?php if(!$showDiv) {?>d-none <?php } ?> create-ticket-row">
        <button class="btn btn-success create-ticket mt-8" style="z-index: 5000;width:126px; height: 470px;margin-top: 18px;">Créer un ticket</button>
        <?php include 'form/_createTicket.php' ?>
    </div>

    <div class="row  <?php if(!$showDiv) {?>d-none <?php } ?> liste-tickets-row">
        <button class="btn btn-success liste-tickets mt-8" style="z-index: 5000;width:126px; height: 470px;margin-top: 18px;">Consulter l'état de mes tickets </button>
        <?php include 'form/_listeMesTickets.php' ?>
    </div>
    <!-- Créer et/ou consulter un ou des ticket(s) -->

     <div class="row animated fadeInLeft d-none create-compte-row-div">
        <button class="btn btn-success create-compte mt-8" style="z-index: 5000;width:126px; height: 400px;">Créer un compte client </button>
        <?php include 'form/_createCustomer.php' ?>
    </div>

</div>
<style>
    .btn-success {
        font-size: 1.2em;
        font-family: 'Solway', serif;
    }
    .fade-in::before {
        position:absolute;
        width: 100px;
        height: 100%;
        background: aquamarine;
        border: 1px solid red;
        z-index: 9999;

    }
    .fade-in {
        opacity: 0;
        //transform: translateX(-100%);
        transform: rotateY(-180deg);;
        transition: all .75s ease-in;
    }

    .fade-in.show {
        opacity: 1;
        //transform: translateX(0);
        transform: rotateY(0deg);
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
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="http://bootstrap-notify.remabledesigns.com/js/bootstrap-notify.min.js"></script>


<script  type="text/javascript">


    $(document).ready(function(){


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


        $(".liste-tickets").click(function(){

            $('.liste-tickets-div').toggleClass(function() {

                if ( $(this).hasClass('show') ) {
                    $(this).parent().parent().find('button.liste-tickets').html('Consulter l\'état de mes tickets');
                    //$('#overlayCustomer').show();
                    $(this).removeClass('show');

                    return "";

                } else {
                    $(this).parent().parent().find('button.liste-tickets').html('Annulez');
                    //$('#overlayCustomer').hide();
                    $(this).addClass('show');

                    return "";

                }
            });

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

    });
</script>

</body>

</html>