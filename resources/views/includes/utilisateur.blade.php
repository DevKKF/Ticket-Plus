<div class="profile-sidebar" style="width: 250px;">
    <div class="portlet light profile-sidebar-portlet">
    <div class="profile-userpic">
        <img src="{{ asset('assets/admin/images/icon/icon.jpg') }}" class="img-responsive" style="width: 55% !important; height: 55% !important;">
    </div>
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">
            <?php echo(html_entity_decode($utilisateur->nom_prenoms)) ?>
        </div>
    </div>
    <div class="profile-userbuttons">
        <a href="{{ route('modifier_utilisateur', $utilisateur->id) }}" class="btn btn-circle green-haze btn-sm">Modifier</a>
    </div>
    <div class="profile-usermenu">
        <ul class="nav">
            <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  45) == '/gestion-des-utilisateurs/details-utilisateur') active @endif">
                <a href="{{ route('details_utilisateur',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" style="font-size: 14px;">
                    <i class="icon-home"></i>
                    Informations 
                </a>
            </li>
            <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  37) == '/gestion-des-utilisateurs/sites-geres') active @endif">
                <a href="{{ route('sites_geres',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" style="font-size: 14px;">
                    <i class="icon-globe"></i>
                    Sites gérées
                </a>
            </li>
            <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  45) == '/gestion-des-utilisateurs/tickets-enregistres') active @endif">
                <a href="{{ route('tickets_enregistres',[$utilisateur->id, Stdfn::clean_url(html_entity_decode($utilisateur->nom_prenoms))]) }}" style="font-size: 14px;">
                    <i class="icon-drawer"></i>
                    Tickets enregistrés
                </a>
            </li>
        </ul>
    </div>
    </div> 
</div>