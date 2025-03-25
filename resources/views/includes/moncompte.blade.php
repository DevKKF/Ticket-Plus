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
    <div class="profile-usermenu">
        <ul class="nav">
            <li class="{{Request::is('mon-compte') ? 'start active ' : ''}}">
                <a href="{{ route('mon_compte') }}" style="font-size: 14px;">
                    <i class="icon-home"></i>
                    Informations 
                </a>
            </li>
            <li class="{{Request::is('mot-de-passe') ? 'start active ' : ''}}">
                <a href="{{ route('changer_mot_passe') }}" style="font-size: 14px;">
                    <i class="icon-lock-open"></i>
                    Mot de passe
                </a>
            </li>
        </ul>
    </div>
    </div> 
</div>