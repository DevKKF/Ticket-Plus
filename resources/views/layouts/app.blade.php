<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8"/>
      <title>{{ config('app.name') }} | @yield('title')</title>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
      <meta http-equiv="Content-type" content="text/html; charset=utf-8">
      <meta content="" name="description"/>
      <meta content="" name="author"/>
      <link rel="shortcut icon" href="" type="image/png">
      <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/global/plugins/select2/select2.css') }}"/ rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/css/components-md.css') }}" id="style_components" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/css/plugins-md.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/admin/layout2/css/layout.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/admin/layout2/css/themes/grey.css') }}" rel="stylesheet" type="text/css" id="style_color">
      <link href="{{ asset('assets/admin/layout2/css/custom.css') }}" rel="stylesheet" type="text/css"/>

      <link href="{{ asset('assets/css/toastr.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/global/plugins/icheck/skins/all.css') }}" rel="stylesheet"/>
      <link href="{{ asset('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css">

      <link href="{{ asset('assets/admin/pages/css/profile.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/admin/pages/css/tasks.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/admin/pages/css/search.css') }}" rel="stylesheet" type="text/css"/>
      <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css"/>

      <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}"/>
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}">
      <link rel="stylesheet" type="text/css" href="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.css') }}">

      <link rel="stylesheet" href="{{ asset('assets/css/krakPopup.css') }}" />

      <script src="{{asset('assets/js/boite_dialogue.js') }}"></script>
      <script src="{{asset('assets/js/ticket-plus.js') }}"></script>

      <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/js/sweetalert2@11.js') }}"></script>
      <script src="{{ asset('assets/js/toastr.min.js') }}"></script>


      <script src="{{ asset('assets/js/highcharts.js') }}"></script>
      <script src="{{ asset('assets/js/exporting.js') }}"></script>
      <script src="{{ asset('assets/js/export-data.js') }}"></script>
      <script src="{{ asset('assets/js/accessibility.js') }}"></script>
      <script src="{{ asset('assets/js/heatmap.js') }}"></script>

      <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <script type="text/javascript">
         var csrf_token = $('meta[name="csrf-token"]').attr('content');
         $.ajaxSetup({
               headers: {
                  "X-CSRF-TOKEN": csrf_token
               }
         });
      </script>

   </head>
   <body class="page-md page-boxed page-header-fixed page-container-bg-solid page-sidebar-closed-hide-logo ">
      <div class="page-header md-shadow-z-1-i navbar navbar-fixed-top">
         <div class="page-header-inner container">
            <div class="page-logo">
               <a href="{{ route('home') }}">
                  <img src="{{ asset('assets/admin/images/logo/logo.png') }}" alt="Tickets Plus" width="135" class="logo-default" style="margin-top:-2px">
               </a>
               <div class="menu-toggler sidebar-toggler">
               </div>
            </div>
            <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse">
            </a>
            <div class="page-top">
               <div class="top-menu">
                  <ul class="nav navbar-nav pull-right">
                     @if(in_array(Auth::user()->profil_id, [1, 2]))
                        <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                           <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                              <i class="icon-bell"></i>
                              <span class="badge badge-default" id="notification-count">0</span>
                           </a>
                           <ul class="dropdown-menu">
                              <li class="external">
                                    <h3><span class="bold"><span id="notification-count-title">0</span> nouvelle(s)</span> demandes</h3>
                                    <a href="{{ route('liste_des_demandes') }}">Tout voir</a>
                              </li>
                              <li>
                                    <ul class="dropdown-menu-list scroller" style="height: 250px; overflow-y: auto" data-handle-color="#637283" id="notification-items">
                                    </ul>
                              </li>
                           </ul>
                        </li>
                     @endif
                     <li class="dropdown dropdown-user">
                        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                           @if(Auth::user()->user_photo)
                              <img alt="" class="img-circle" src="{{ asset('assets/admin/images/profil/' . Auth::user()->user_photo) }}">
                           @else
                              <img alt="" class="img-circle" src="{{ asset('assets/admin/layout2/img/avatar3_small.jpg') }}">
                           @endif
                           <span class="username username-hide-on-mobile" style="color:#000; font-weight:bold">{{ Auth::user()->nom_prenoms }}</span>
                           <i class="fa fa-angle-down" style="color:#000; font-weight:bold"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                           <li>
                              <a href="{{ route('mon_compte') }}">
                              <i class="icon-user"></i> Mon compte </a>
                           </li>
                           <!--li>
                              <a href="">
                              <i class="icon-envelope"></i> Messages </a>
                           </li-->
                           <li>
                              <a href="{{ route('changer_mot_passe') }}">
                              <i class="icon-lock-open"></i> Mot de passe </a>
                           </li>
                           <li>
                              <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                 <i class="icon-key"></i> Se déconnecter 
                                 <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                 </form>
                              </a>
                           </li>
                        </ul>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <div class="clearfix"></div>
      <div class="container">
         <div class="page-container">
            <div class="page-sidebar-wrapper">
               <div class="page-sidebar navbar-collapse collapse">
                  <ul class="page-sidebar-menu page-sidebar-menu-hover-submenu " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                     <li class="{{Request::is('home','/') ? 'start active ' : ''}}">
                        <a href="{{ route('home') }}">
                           <i class="icon-home"></i>
                           <span class="title">Tableau de bord</span>
                           <span class="selected"></span>
                        </a>
                     </li>
                     @if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_002") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_005") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_007"))
                        <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  31) == '/gestion-des-sites/details-site' or substr($_SERVER['REQUEST_URI'], 0,  32) == '/gestion-des-sites/modifier-site' or substr($_SERVER['REQUEST_URI'], 0,  38) == '/gestion-des-sites/nouveau-ticket-site') active @endif {{Request::is('gestion-des-sites/ajouter-un-site', 'gestion-des-sites/liste-des-sites', '', '', '', '', '', '') ? 'active open' : ''}}">
                           <a href="javascript:;">
                           <i class="icon-globe"></i>
                           <span class="title">Gestion des sites</span>
                           <span class="selected"></span>
                           <span class="arrow open"></span>
                           </a>
                           <ul class="sub-menu">
                              @if(in_array(Auth::user()->profil_id, [1, 2]))
                                 <li class="{{Request::is('gestion-des-sites/ajouter-un-site') ? 'active' : ''}}">
                                    <a href="{{ route('ajouter_site') }}">Ajouter un site</a>
                                 </li>
                              @endif 
                              <li class="{{Request::is('gestion-des-sites/liste-des-sites') ? 'active' : ''}}">
                                 <a href="{{ route('liste_site') }}">Liste des sites</a>
                              </li>
                           </ul>
                        </li>
                     @endif 
                     @if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_003") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_006") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_007"))
                        <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  35) == '/gestion-des-tickets/details-ticket' or substr($_SERVER['REQUEST_URI'], 0,  36) == '/gestion-des-tickets/modifier-ticket' or substr($_SERVER['REQUEST_URI'], 0,  46) == '/gestion-des-tickets/details-historique-ticket') active @endif {{Request::is('gestion-des-tickets/ajouter-un-ticket', 'gestion-des-tickets/liste-des-tickets', 'gestion-des-tickets/ticket-pm', 'gestion-des-tickets/ticket-cm', 'gestion-des-tickets/autres-tickets', '', '', '') ? 'active open' : ''}}">
                           <a href="javascript:;">
                           <i class="icon-drawer"></i>
                           <span class="title">Gestion des tickets</span>
                           <span class="selected"></span>
                           <span class="arrow open"></span>
                           </a>
                           <ul class="sub-menu">
                              @if(in_array(Auth::user()->profil_id, [1, 2]))
                                 <li class="{{Request::is('gestion-des-tickets/ajouter-un-ticket') ? 'active' : ''}}">
                                    <a href="{{ route('ajouter_ticket') }}">Ajouter un ticket</a>
                                 </li>
                              @endif
                              <li class="{{Request::is('gestion-des-tickets/liste-des-tickets') ? 'active' : ''}}">
                                 <a href="{{ route('liste_ticket') }}">Liste des tickets</a>
                              </li>
                           </ul>
                        </li>
                     @endif
                     @if(Auth::user()->profil_id == 1 or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_001") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_004") or Stdfn::isActionAutorisee(Auth::user()->id, "ACC_007"))
                        <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  46) == '/gestion-des-utilisateurs/modifier-utilisateur' or substr($_SERVER['REQUEST_URI'], 0,  45) == '/gestion-des-utilisateurs/details-utilisateur' or substr($_SERVER['REQUEST_URI'], 0,  37) == '/gestion-des-utilisateurs/sites-geres' or substr($_SERVER['REQUEST_URI'], 0,  45) == '/gestion-des-utilisateurs/tickets-enregistres') active @endif {{Request::is('gestion-des-utilisateurs/ajouter-un-utilisateur', 'gestion-des-utilisateurs/liste-des-utilisateurs', '', '', '', '', '', '') ? 'active open' : ''}}">
                           <a href="javascript:;">
                           <i class="icon-users"></i>
                           <span class="title">Gestion des utilisateurs</span>
                           <span class="selected"></span>
                           <span class="arrow open"></span>
                           </a>
                           <ul class="sub-menu">
                              @if(in_array(Auth::user()->profil_id, [1, 2]))
                                 <li class="{{Request::is('gestion-des-utilisateurs/ajouter-un-utilisateur') ? 'active' : ''}}">
                                    <a href="{{ route('ajouter_utilisateur') }}">Ajouter un utilisateur</a>
                                 </li>
                              @endif
                              <li class="{{Request::is('gestion-des-utilisateurs/liste-des-utilisateurs') ? 'active' : ''}}">
                                 <a href="{{ route('liste_utilisateur') }}">Liste des utilisateurs</a>
                              </li>
                           </ul>
                        </li>
                     @endif
                     <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  00) == '/') active @endif {{Request::is('statistiques/statistiques-journaliere', '', '') ? 'active open' : ''}}">
                        <a href="javascript:;">
                           <i class="icon-bar-chart"></i>
                           <span class="title">Statistiques</span>
                           <span class="selected"></span>
                           <span class="arrow open"></span>
                        </a>
                        <ul class="sub-menu">
                           <li class="{{Request::is('statistiques/statistiques-journaliere') ? 'active' : ''}}">
                              <a href="{{ route('statistiques_journaliere') }}">Statistiques journalière</a>
                           </li>
                           <li class="{{Request::is('') ? 'active' : ''}}">
                              <a href="">Statistiques mensuelle</a>
                           </li>
                           <li class="{{Request::is('') ? 'active' : ''}}">
                              <a href="">Statistiques annuelle</a>
                           </li>
                        </ul>
                     </li>
                     @if(Auth::user()->profil_id == 1)
                        <li class="@if(substr($_SERVER['REQUEST_URI'], 0,  00) == '/') active @endif {{Request::is('gestion-des-parametres/zones', 'gestion-des-parametres/regions', 'gestion-des-parametres/types-actions', 'gestion-des-parametres/operateurs', 'gestion-des-parametres/priorites-ihs', 'gestion-des-parametres/topologies-typologies', 'gestion-des-parametres/actions-tickets', '') ? 'active open' : ''}}">
                           <a href="javascript:;">
                           <i class="icon-settings"></i>
                           <span class="title">Gestion des paramètres</span>
                           <span class="selected"></span>
                           <span class="arrow open"></span>
                           </a>
                           <ul class="sub-menu">
                              <li class="{{Request::is('gestion-des-parametres/regions') ? 'active' : ''}}">
                                 <a href="{{ route('gestion_region') }}">Régions</a>
                              </li>
                              <li class="{{Request::is('gestion-des-parametres/types-actions') ? 'active' : ''}}">
                                 <a href="{{ route('gestion_type_action') }}">Types d'action</a>
                              </li>
                              <li class="{{Request::is('gestion-des-parametres/zones') ? 'active' : ''}}">
                                 <a href="{{ route('gestion_zone') }}">Zones</a>
                              </li>
                              <li class="{{Request::is('gestion-des-parametres/operateurs') ? 'active' : ''}}">
                                 <a href="{{ route('save_operateur') }}">Operateurs</a>
                              </li>
                              <li class="{{Request::is('gestion-des-parametres/priorites-ihs') ? 'active' : ''}}">
                                 <a href="{{ route('gestion_priorite_ihs') }}">Priorités IHS</a>
                              </li>
                              <li class="{{Request::is('gestion-des-parametres/topologies-typologies') ? 'active' : ''}}">
                                 <a href="{{ route('gestion_topologie_typologie') }}">Topologies / Typologies</a>
                              </li>
                              <li class="{{Request::is('gestion-des-parametres/actions-tickets') ? 'active' : ''}}">
                                 <a href="{{ route('gestion_action_ticket') }}">Actions Tickets</a>
                              </li>
                           </ul>
                        </li>
                     @endif
                  </ul>
               </div>
            </div>
            <div class="page-content-wrapper">
               <div class="page-content">
                  @yield('content')
               </div>
            </div>
         </div>
         <div class="page-footer">
            <div class="page-footer-inner">
               <?php echo(gmdate('Y')) ?> © <b>{{ env('APP_TITLE') }}</b>
               <span class="ml-5">{{ env('APP_VERSION') }}</span>
            </div>
            <div class="scroll-to-top">
               <i class="icon-arrow-up"></i>
            </div>
         </div>
      </div>
      <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery-migrate.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery-ui/jquery-ui.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/select2/select2.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/datatables/media/js/jquery.dataTables.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
      
      <script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript" ></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript" ></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-markdown/lib/markdown.js') }}" type="text/javascript" ></script>

      
      <script src="{{ asset('assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-summernote/summernote.min.js') }}" type="text/javascript"></script>

      <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/admin/pages/scripts/form-wizard.js') }}" type="text/javascript"></script>

      <script src="{{ asset('assets/admin/layout2/scripts/layout.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/admin/layout2/scripts/demo.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/admin/pages/scripts/form-samples.js') }}"></script>
      <script src="{{ asset('assets/admin/pages/scripts/table-managed.js') }}"></script> 
      <script src="{{ asset('assets/admin/pages/scripts/form-wizard.js') }}"></script>
      <script src="{{ asset('assets/admin/pages/scripts/form-validation.js') }}"></script>
      <script src="{{ asset('assets/admin/pages/scripts/todo.js') }}" type="text/javascript"></script>

      <script src="{{ asset('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/global/plugins/jquery.sparkline.min.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/admin/pages/scripts/profile.js') }}" type="text/javascript"></script>
      <script src="{{ asset('assets/admin/pages/scripts/components-editors.js') }}"></script>

      <script>
         jQuery(document).ready(function() {  
            Metronic.init();
            Layout.init();
            Demo.init();
            FormSamples.init();
            TableManaged.init();
            FormWizard.init();
            Profile.init();
            Todo.init();
            FormValidation.init();
            ComponentsEditors.init();
         });
      </script>

      <script>
        @if(Session::has('echec'))
            toastr.error("{{ Session::get('echec') }}", "MESSAGE D'ERREUR")
        @endif

        @if(Session::has('error'))
            toastr.error("{{ Session::get('error') }}", "MESSAGE D'ERREUR")
        @endif

        @if(Session::has('succes'))
            toastr.success("{{ Session::get('succes') }}", "MESSAGE")
        @endif

        @if(Session::has('success'))
            toastr.success("{{ Session::get('success') }}", "MESSAGE")
        @endif

        @if(Session::has('warning'))
            toastr.warning("{{ Session::get('warning') }}", "MESSAGE D'ERREUR")
        @endif

        @if(Session::has('session_expired'))
            toastr.primary("{{ Session::get('session_expired') }}", "CONNEXION EXPIREE")
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                toastr.error("{{ $error }}", "MESSAGE D'ERREUR")
            @endforeach
        @endif

         function isInputNumber(evt){
        
            var ch = String.fromCharCode(evt.which);
              
            if(!(/[0-9]/.test(ch))){
               evt.preventDefault();
            }        
         } 
      </script>
      <script src="{{ asset('assets/js/kraksoft.js') }}"></script>
      <script src="{{asset('assets/js/jquery.krakPopup.js') }}"></script>
      <script src="{{asset('assets/js/ticket-plus.js') }}"></script>

      <script src="{{ asset('assets/js/noty/jquery.noty.js') }}"></script>
      <script src="{{ asset('assets/js/noty/layouts/bottomCenter.js') }}"></script>
      <script src="{{ asset('assets/js/noty/layouts/topRight.js') }}"></script>
      <script src="{{ asset('assets/js/noty/layouts/top.js') }}"></script>
      <script src="{{ asset('assets/js/noty/layouts/center.js') }}"></script>
      <script src="{{ asset('assets/js/noty/themes/default.js') }}"></script>

      <script>
         document.addEventListener('DOMContentLoaded', function() {
            function updateNotificationUI(count, notifications) {
               const countElement = document.getElementById('notification-count');
               const countTitleElement = document.getElementById('notification-count-title');
               const itemsContainer = document.getElementById('notification-items');
               
               if (countElement) countElement.textContent = count;
               if (countTitleElement) countTitleElement.textContent = count;
               if (itemsContainer) itemsContainer.innerHTML = '';

               if (count > 0 && itemsContainer) {
                  notifications.forEach(notification => {
                     const li = document.createElement('li');
                     li.innerHTML = `
                        <a href="${notification.details}" class="notification-item">
                           <span class="time" style="font-size:9px !important">${notification.temps}</span>
                           <span class="details">
                              <span class="label label-sm label-icon label-success">
                                 <i class="fa fa-bullhorn"></i>
                              </span>
                              <span class="notification-text" style="font-size:11px !important">${notification.titre}</span>
                           </span>
                        </a>
                     `;
                     itemsContainer.appendChild(li);
                  });
               } else if (itemsContainer) {
                  itemsContainer.innerHTML = '<li><a>Aucune nouvelle demande.</a></li>';
               }
            }

            async function checkNotifications() {
               try {
                  const response = await fetch('/api/notifications', {
                     method: 'GET',
                     headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                     }
                  });

                  if (!response.ok) throw new Error('Erreur réseau');
                  
                  const data = await response.json();
                  updateNotificationUI(data.count, data.notifications);
               } catch (error) {
                  console.error('Erreur lors de la récupération des notifications:', error);
               }
            }

            // Première vérification
            checkNotifications();

            // Vérification toutes les 30 secondes
            setInterval(checkNotifications, 30000);
         });
      </script>

      <input type="hidden" id="eco_base_url" name="eco_base_url" value="{{ Request::isSecure() ? 'https://' : 'http://' }}{{ $_SERVER['HTTP_HOST'] }}/">
   </body>
</html>
