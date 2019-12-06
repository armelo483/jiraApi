
<div  style="overflow: hidden;margin-top: 18px;" class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">
           <div class="create-form-div create-project-div">
               <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>">
                   <fieldset>
                       <!-- Form Name -->
                       <legend> Formulaire de création de projets</legend>
                       <!-- Select Basic -->
                       <div class="form-group">
                           <label class="col-md-5 control-label" for="selectbasic">Type de ticket à créer</label>
                           <div class="col-md-9">
                               <select id="selectbasic" name="selectbasic" class="form-control">
                                   <option value="15">Nouvelle fonctionnalité</option>
                                   <option value="14">Bug</option>
                                   <option value="16">Suggérer une amélioration</option>
                               </select>
                           </div>
                       </div>


                       <!-- Text input-->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="textinput">Résumé </label>
                           <div class="col-md-9">
                               <input id="textinput" name="textinput" type="text" placeholder="Resumer en quoi consiste voter ticket" class="form-control input-md">
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
                           <label class="col-md-3 control-label" for="textarea">Description</label>
                           <div class="col-md-9">
                               <textarea class="form-control" id="textarea" name="textarea" placeholder="La description de votre ticket"></textarea>
                           </div>
                       </div>

                       <!-- Button (Double) -->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="button1id"></label>
                           <div class="col-md-8">
                               <button id="button1id" name="button1id" class="btn btn-success" type="submit">Envoyer ma demande</button>
                           </div>
                       </div>

                   </fieldset>
               </form>
           </div>

        </div>