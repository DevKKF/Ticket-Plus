<div class="modal fade" id="ImporterSite" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Importer des sites</h4>
            </div>
            <form method="POST" action="{{ route('save_importer_site') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Fichier (Excel) <span class="text-danger">*</span></label>
                        <input type="file" name="fichier" id="fichier" class="form-control" accept=".xlsx" required>
                    </div> 
                    <div class="form-group">
                        <label>Exemple de fichier (Excel) à charger</label>
                        <div style="overflow-x: auto; max-width: 100%;">
                            <img src="{{ asset('assets/admin/images/capture/excel_export.png') }}" alt="" style="min-width: 800px;">
                        </div>
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