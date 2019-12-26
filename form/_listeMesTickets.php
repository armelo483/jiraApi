
<div style="overflow: hidden;" class="parent-liste col-xs-12 col-sm-12 col-md-12 col-lg-10  col-lg-offset-3">
           <div class="create-form-div liste-tickets-div fade-in mt-3 pt-3 pl-3">
               <h3>Liste de mes tickets créés ou suivis(request participant)</h3>
               <table class="table" id="accordion">
                   <thead>
                   <tr>
                       <th>Rapporteur</th>
                       <th>Resumé</th>
                       <th>Etat</th>
                       <th>Date de création</th>
                       <th>Type de tickets</th>
                       <th>Echanges sur le ticket</th>
                       <th>Détail du ticket</th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php  foreach($filteredOwnerTicketArray as $key=>$ticket) { ?>
                       <tr>
                           <td><?php echo $ticket->reporter->displayName; ?></td>
                           <td><?php echo $ticket->requestFieldValues[0]->value; ?></td>
                           <td><?php echo $ticket->currentStatus->status; ?></td>
                           <td><?php echo $ticket->createdDate->friendly; ?></td>
                           <td><?php echo $ticketTypeArr[$ticket->requestTypeId]; ?></td>
                           <!--<td><button type="button" class="btn btn-primary afficher-comment" data-toggle="modal" data-target="#fullHeightModalRight">Afficher commentaires</button></td>-->
                           <td><button type="button" class="btn btn-primary afficher-comment">Afficher commentaires</button>
                               <input type="hidden" id="<?php echo $ticket->issueId ?>" value="<?php echo $ticket->issueId ?>"/>
                           </td>
                           <td>
                               <button type="button" class="btn btn-primary afficher-detail-ticket" data-toggle="collapse" href="#collapseOne<?php echo $ticket->issueId ?>">détail</button>
                           </td>

                       </tr>
                       <tr class="collapse-tr" style="border:0;">
                           <td colspan="7" height="0" style="border: 0;">
                               <div style="color:#fff; background: darkgrey;height: 177px;" id="collapseOne<?php echo $ticket->issueId ?>" class="collapse" data-parent="#accordion">
                                   <div class="container">
                                       <?php
                                       //On retire les champs déjà affichés plus haut dans le tableau et aussi ceux qui sont inutiles pour le détail
                                       $ticket->requestFieldValues =array_filter($ticket->requestFieldValues,function($value) {
                                           return ($value->fieldId!='summary'&&$value->fieldId!='attachment'&&$value->fieldId!='components');
                                       });
                                       foreach($ticket->requestFieldValues as $key=>$ticketRequestedFieldItem) { ?>
                                       <div class="row">
                                           <div class="col-md-2 pull-right">
                                               <b style="display: inline-block; font-weight: bolder;"><u><?php echo strtoupper($ticketRequestedFieldItem->fieldId); ?>:</u></b>
                                           </div>
                                           <div class="col-md-10">
                                               <?php echo $ticketRequestedFieldItem->value; ?>
                                           </div>
                                       </div>
                                        <?php } ?>
                                   </div>
                               </div>
                           </td>
                       </tr>
                   <?php } ?>
                   </tbody>
               </table>
           </div>

        </div>

<!-- Full Height Modal Right  -->
<div class="modal fade right" id="fullHeightModalRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" >

    <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
    <div class="modal-dialog modal-full-height modal-right" role="document">

        <div class="modal-content">
            <div class="modal-header" style="background: lemonchiffon; margin-bottom: 7px;">
                <h4 class="modal-title w-100" id="myModalLabel">Commentaires du ticket</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-bod" id="modalBody" style="overflow: hidden;">
                ...
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <!--<button type="button" class="btn btn-primary">Save changes</button>-->
            </div>
        </div>
    </div>
</div>
<!-- Full Height Modal Right  -->






