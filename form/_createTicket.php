
<div  style="margin-top: 18px;" class="col-xs-12 col-sm-10 col-md-10 col-lg-10">
    <div class="christmas-hat"></div>
    <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="post" enctype='multipart/form-data' id="ticketForm">
    <div class="overlay" id="overlayTicket" style="position: absolute;width:100%;height:100%; z-index: 9999;"></div>

            <div class="create-form-div create-ticket-div fade-in col-sm-9 col-md-7 col-lg-7" style="display: inline-block;">
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
                                   <option value="22">Ticket de type Osac</option>
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
                               <button id="createTicket" name="submitCreateTicket" class="btn btn-success" type="submit">Envoyer ma demande</button>
                           </div>
                       </div>

                   </fieldset>

           </div>

        <!-- EXTRA FIELD DIV -->
        <div class="card create-form-div-extra">
            <div class="card-header"> <i class="far fa-address-card fa-spin fa" style="margin-right: 10px"></i>Champs supplémentaires</div>
            <div class="card-body">

                <fieldset>
                    <!-- Select Basic -->
                    <div class="form-group">
                        <label class="col-md-10 control-label" for="selectbasic">Numéro de commande</label>
                        <div class="col-md-9">
                            <input id="textinput" name="numerodecommande" type="text" placeholder="Votre numéro de commande" class="form-control input-md resume">
                        </div>
                    </div>

                    <!-- Text input-->
                    <div class="form-group">
                        <label class="col-md-10 control-label" for="textinput">Numéro de facture </label>
                        <div class="col-md-9">
                            <input id="textinput" name="numerodefacture" type="text" placeholder="Numéro de facture" class="form-control input-md resume">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-10 control-label" for="exampleFormControlFile1">Terrain</label>
                        <div class="col-md-9">
                            <input id="textinput" name="terrain" type="text" placeholder="Terrain" class="form-control input-md resume">
                        </div>
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
                        <label class="col-md-10 control-label" for="textarea">Agrément</label>
                        <div class="col-md-9">
                            <input id="textinput" name="agrement" type="text" placeholder="Agrément" class="form-control input-md resume">
                        </div>
                    </div>
                   <!--
                    <div class="form-group">
                        <label class="col-md-10 control-label" for="textarea">Nom demandeur</label>
                        <div class="col-md-9">
                            <input id="textinput" name="nomdemandeur" type="text" placeholder="Nom demandeur" class="form-control input-md resume">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-10 control-label" for="textarea">Date et heure</label>
                        <div class="col-md-9">
                            <input id="textinput" name="dateheure" type="text" placeholder="Date et heure" class="form-control input-md resume">
                        </div>
                    </div>
-->
                    <div class="form-group">
                        <label class="col-md-10 control-label" for="textarea">Responsable</label>
                        <div class="col-md-9">
                            <input id="textinput" name="responsable" type="text" placeholder="Responsable" class="form-control input-md resume">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-10 control-label" for="textarea">Type de service</label>
                        <div class="col-md-9">
                            <input id="textinput" name="typedeservice" type="text" placeholder="Type de service" class="form-control input-md resume">
                        </div>
                    </div>

                </fieldset>

            </div>
            <div class="card-footer"></div>
        </div>

        <!-- FIN EXTRA FIELD DIV -->
</form>
        </div>






    <!-- Extra field div -->
