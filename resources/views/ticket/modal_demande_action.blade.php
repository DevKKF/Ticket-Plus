@foreach($tickets as $ticket)
    <div class="modal fade" id="DemandeActionTicket{{ $ticket->ticket_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Faire une demande d'action sur le ticket</h4>
                </div>
                <form method="POST" action="{{ route('save_demande_action_ticket', $ticket->ticket_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Type de demande <span class="text-danger">*</span></label>
                            <select name="action_ticket_id" id="action_ticket_id" class="form-control" required>
                                <option value="">Choisir</option>
                                @foreach($actionticket as $action)
                                    <option value="{{ $action->action_ticket_id }}">{{ $action->action_ticket_nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date de la demande <span class="text-danger">*</span></label>
                            <input type="date" name="demande_date" id="demande_date" value="<?php echo(gmdate('Y-m-d')) ?>" class="form-control" required readonly>
                        </div>
                        <div class="form-group">
                            <label>Demande à traiter <span class="text-danger">*</span></label>
                            <input type="text" name="demande_a_traiter" id="demande_a_traiter" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description de la demande <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="description" cols="30" rows="10" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" style="float: left !important;" data-dismiss="modal" style=""><i class="fa fa-remove"></i> Fermer</button>
                        <button type="submit" class="btn btn-success" style="float: right !important;" id="formSubmit"><i class="fa fa-check-circle"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach