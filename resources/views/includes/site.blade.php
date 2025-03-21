<style>
    .note-white {
        background: #fff;
    }

    .border_right{
        border-right: 2px solid #F16623;
        height: auto;
    }

</style>
<div class="note note-white note-bordered">
    <div class="row">
        <div class="col-md-6 border_right">
            <div class="row static-info">
                <div class="col-md-5 name">
                    Code : 
                </div>
                <div class="col-md-7 value">
                    <span class="badge badge-success">{{ $site->site_ihs }}</span>
                </div>
            </div>  
            <div class="row static-info">
                <div class="col-md-5 name">
                    Nom : 
                </div>
                <div class="col-md-7 value">
                    <?php echo(html_entity_decode($site->site_nom)) ?>
                </div>
            </div>
            <div class="row static-info">
                <div class="col-md-5 name">
                    Opérateur : 
                </div>
                <div class="col-md-7 value">
                    <?php echo(html_entity_decode($site->operateur_nom)) ?>
                </div>
            </div>
            <div class="row static-info">
                <div class="col-md-5 name">
                    MM : 
                </div>
                <div class="col-md-7 value">
                    {{ $site->site_nom_mm }}
                </div>
            </div>   
            <div class="row static-info">
                <div class="col-md-5 name">
                    SBC : 
                </div>
                <div class="col-md-7 value">
                    {{ $site->site_sbc }}
                </div>
            </div>  
            <div class="row static-info">
                <div class="col-md-5 name">
                    Date d'enregistrement : 
                </div>
                <div class="col-md-7 value">
                    {{ Stdfn::dateTimeFromDB($site->site_datecrea) }}
                </div>
            </div>  
        </div>
        <div class="col-md-6">
            <div class="row static-info">
                <div class="col-md-5 name">
                    Région : 
                </div>
                <div class="col-md-7 value">
                    <?php echo(html_entity_decode($site->region_nom)) ?>
                </div>
            </div>  
            <div class="row static-info">
                <div class="col-md-5 name">
                    Zone : 
                </div>
                <div class="col-md-7 value">
                    <?php echo(html_entity_decode($site->zone_nom)) ?>
                </div>
            </div> 
            <div class="row static-info">
                <div class="col-md-5 name">
                    Priorité IHS : 
                </div>
                <div class="col-md-7 value">
                    <?php echo(html_entity_decode($site->priorite_ihs_nom)) ?>
                </div>
            </div> 
            <div class="row static-info">
                <div class="col-md-5 name">
                    RM : 
                </div>
                <div class="col-md-7 value">
                    {{ $site->site_nom_rm }}
                </div>
            </div>   
            <div class="row static-info">
                <div class="col-md-5 name">
                    Date de création : 
                </div>
                <div class="col-md-7 value">
                    {{ Stdfn::dateFromDB($site->site_date_creation) }}
                </div>
            </div>   
            <div class="row static-info">
                <div class="col-md-5 name">
                    Statut : 
                </div>
                <div class="col-md-7 value">
                    <span class="badge badge-<?php echo(str_replace(' ','', $site->site_statut)); ?>">{{ $site->site_statut }}</span>
                </div>
            </div>
        </div>
    </div>
</div>