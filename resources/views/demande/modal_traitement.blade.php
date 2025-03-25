@foreach($demandes as $demande)
    <div class="modal fade" id="DemandeAction{{ $demande->demande_id }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                    <h4 class="modal-title">Traitement de la demande</h4>
                </div>
                <form method="POST" action="{{ route('traitement_demande', $demande->demande_id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Action <span class="text-danger">*</span></label>
                            <select name="action" id="action_traitement" class="form-control" required>
                                <option value="">Choisir</option>
                                <option value="ACCEPTE">CONFIRMER</option>
                                <option value="ANNULE">ANNULER</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date action <span class="text-danger">*</span></label>
                            <input type="date" name="demande_date" id="demande_date" value="<?php echo(gmdate('Y-m-d')) ?>" class="form-control" required readonly>
                        </div>
                        <div class="form-group" id="description_champ" style="display:none">
                            <label>Description de l'action <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="description" id="description" cols="10" rows="3"></textarea>
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
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let actionSelect = document.getElementById("action_traitement");
        let descriptionField = document.querySelector(".description_champ");
        let descriptionTextarea = document.getElementById("description");

        actionSelect.addEventListener("change", function () {
            if (this.value === "ANNULE") {
                descriptionField.style.display = "block";
                descriptionTextarea.setAttribute("required", "required");
            } else {
                descriptionField.style.display = "none";
                descriptionTextarea.removeAttribute("required");
            }
        });
    });
</script>


@endforeach