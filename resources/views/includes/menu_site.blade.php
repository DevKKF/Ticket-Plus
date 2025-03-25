<style>
    .font-16{
        font-size:16px !important;
    }

    .font-14{
        font-size:14px !important;
    }

    .font-12{
        font-size:12px !important;
    }

    .font-11{
        font-size:11px !important;
    }

    .font-10{
        font-size:10px !important;
    }

    .font-8{
        font-size:8px !important;
    }

    .font-6{
        font-size:6px !important;
    }

    .font-4{
        font-size:4px !important;
    }

    .nav-tabs{
        border-bottom: 1px solid #ddd;
        background: #DA741E;
    }

    .font-bold{
        font-weight:bold;
    }

    .button-tabs a:hover{
        color:#fff !important;
    }

    .tabbable-custom.tabbable-noborder > .nav-tabs > li > a {
        border: 0;
        color: #000 !important;
    }

</style>
<ul class="nav nav-tabs button-tabs">
    <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  31) == '/gestion-des-sites/details-site') active text-black @endif text-uppercase">
        <a class="font-bold text-white font-14" href="{{ route('details_site',[$site->site_id, Stdfn::clean_url(html_entity_decode($site->site_ihs))]) }}">
        <i class="icon-list"></i> Liste des tickets</a>
    </li>
    @if(in_array(Auth::user()->profil_id, [1, 2]))
        <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  38) == '/gestion-des-sites/nouveau-ticket-site') active text-black @endif text-uppercase">
            <a class="font-bold text-white font-14" href="{{ route('nouveau_ticket_site',[$site->site_id, Stdfn::clean_url(html_entity_decode($site->site_ihs))]) }}">
            <i class="icon-drawer"></i> Nouveau ticket</a>
        </li>
    @endif
</ul>
