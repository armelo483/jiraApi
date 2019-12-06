
<div  style="overflow: hidden;" class="col-xs-12 col-sm-12 col-md-8 col-lg-6  column col-sm-offset-0 col-md-offset-2 col-lg-offset-3">
    <div class="overlay" id="overlayCustomer" style="position: absolute;width:100%;height:100%; z-index: 9999;"></div>
           <div class="create-form-div create-customer-div fade-in">
               <form class="form-horizontal" action="<?=$_SERVER['PHP_SELF'];?>" method="post" id="customerForm">
                   <fieldset>
                       <!-- Form Name -->
                       <legend> Formulaire de création de compte client</legend>
                       <!-- Select Basic -->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="selectbasic">Nom:</label>
                           <div class="col-md-9">
                               <input id="textinput" name="displayName" type="text" placeholder="Votre nom" class="form-control input-md nom">
                           </div>
                       </div>


                       <!-- Text input-->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="textinput">Email: </label>
                           <div class="col-md-9">
                               <input id="textinput" name="email" type="text" placeholder="Résumer en quoi consiste votre ticket" class="form-control input-md email">
                           </div>

                       </div>

                       <!-- Button (Double) -->
                       <div class="form-group">
                           <label class="col-md-3 control-label" for="button1id"></label>
                           <div class="col-md-8">
                               <button id="button1id" name="submitCreateCustomer" class="btn btn-success" type="submit">Je crée mon compte</button>
                           </div>
                       </div>
                   </fieldset>
               </form>
           </div>

        </div>