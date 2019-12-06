
<div  style="overflow: hidden;margin-top: 18px;" class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">
    <div class="overlay" id="overlayTicket" style="position: absolute;width:100%;height:100%; z-index: 9999;"></div>
            <div class="create-form-div create-ticket-div fade-in" >
               <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype='multipart/form-data' id="ticketForm">
                   <fieldset>
                       <!-- Form Name -->
                       <legend> Formulaire de création de tickets</legend>
                       <!-- Select Basic -->
                       <div class="form-group">
                           <label class="col-md-5 control-label" for="selectbasic">Type de ticket à créer</label>
                           <div class="col-md-9">
                               <select id="selectbasic" name="type-ticket" class="form-control">
                                   <option value="15">Nouvelle fonctionnalité</option>
                                   <option value="14">Signaler un bug</option>
                                   <option value="16">Suggérer une amélioration</option>
                                   <option value="10">Question sur la facturation et la license</option>
                                   <option value="12">Autre question</option>
                                   <option value="9">Support technique</option>
                                   <option value="11">Question sur la version d'essai d'un produit</option>
                                   <option value="13">Requête reçue par mail</option>
                               </select>
                           </div>
                       </div>


                       <!-- Text input-->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="textinput">Résumé </label>
                           <div class="col-md-9">
                               <input id="textinput" name="resume" type="text" placeholder="Résumer en quoi consiste voter ticket" class="form-control input-md resume">
                           </div>
                       </div>


                       <div class="form-group">
                           <label class="col-md-3 control-label" for="exampleFormControlFile1">Pièce jointe</label>
                           <input type="file" class="form-control-file" name="pieceJointes[]" id="exampleFormControlFile1" multiple>
                       </div>
                       <!--<div class="form-group">
                           <label class="col-md-3 control-label" for="textinput">Desde: </label>
                           <div class="col-md-4">
                               <input id="textinput" name="textinput" type="date" placeholder="placeholder" class="form-control input-md">
                           </div>

                           <label class="col-md-1 control-label" for="textinput">Hasta: </label>
                           <div class="col-md-4">
                               <input id="textinput" name="textinput" type="date" placeholder="placeholder" class="form-control input-md">
                           </div>
                       </div>-->

                       <!-- Textarea -->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="textarea">Description</label>
                           <div class="col-md-9">
                               <textarea class="form-control description" id="textarea" name="description" placeholder="La description de votre ticket"></textarea>
                           </div>
                       </div>

                       <!-- Button (Double) -->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="button1id"></label>
                           <div class="col-md-8">
                               <button id="button1id" name="submitCreateTicket" class="btn btn-success" type="submit">Envoyer ma demande</button>
                           </div>
                       </div>

                   </fieldset>
               </form>
           </div>

        </div>