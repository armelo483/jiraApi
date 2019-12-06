
<div style="overflow: hidden;" class="col-xs-12 col-sm-12 col-md-12 col-lg-10  col-lg-offset-3">
           <div class="create-form-div liste-tickets-div fade-in mt-3">
               <h3>Liste de mes tickets créés ou suivis(request participant)</h3>
               <table class="table">
                   <thead>
                   <tr>
                       <th>Rapporteur</th>
                       <th>Resumé</th>
                       <th>Etat</th>
                       <th>Date de création</th>
                       <th>Type de tickets</th>
                   </tr>
                   </thead>
                   <tbody>
                   <?php foreach($filteredOwnerTicketArray as $key=>$ticket) { ?>
                       <tr>
                           <td><?php echo $ticket->reporter->displayName; ?></td>
                           <td><?php echo $ticket->requestFieldValues[0]->value; ?></td>
                           <td><?php echo $ticket->currentStatus->status; ?></td>
                           <td><?php echo $ticket->createdDate->friendly; ?></td>
                           <td><?php echo $ticketTypeArr[$ticket->requestTypeId]; ?></td>
                       </tr>
                   <?php } ?>
                   </tbody>
               </table>
           </div>

        </div>