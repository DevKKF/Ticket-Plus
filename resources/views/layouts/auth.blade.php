<!DOCTYPE html>
      <html lang="fr">
         <head>
            <meta charset="utf-8"/>
            <title>{{ config('app.name') }} | @yield('title')</title>
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
            <meta http-equiv="Content-type" content="text/html; charset=utf-8">
            <meta content="" name="description"/>
            <meta content="" name="author"/>
             <link rel="shortcut icon" href="{{ asset('assets/admin/images/logo/logo.jpg') }}" type="image/png">
            <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/plugins/uniform/css/uniform.default.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/admin/pages/css/login.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/css/components-md.css') }}" id="style_components" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/css/plugins-md.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/admin/layout/css/layout.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/admin/layout/css/themes/default.css') }}" rel="stylesheet" type="text/css" id="style_color"/>
            <link href="{{ asset('assets/admin/layout/css/custom.css') }}" rel="stylesheet" type="text/css"/>
            <link href="{{ asset('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css"/>
            <style>
               .btn-warning{
                  background:#D0AF1C;
               }

               .btn-warning:hover{
                  background:#B11F2A;
               }
               
            </style>
         </head>
         <body class="page-md login" style="background: url({{ asset('assets/admin/images/background.jpg') }}) no-repeat center center; background-size: cover;">
            <div class="menu-toggler sidebar-toggler"></div>
            <div class="logo">
               <img src="{{ asset('assets/admin/images/logo/.jpg') }}" alt="">
            </div>
            <div class="content"> 
               @yield('content')
            </div>
            <div class="copyright" style="color: #000 !important; font-weight:bold">
               <?php echo(gmdate('Y')) ?> © <b>{{ env('APP_NAME') }}</b>
            </div>
            <script src="{{ asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery-migrate.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery.blockui.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/uniform/jquery.uniform.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery.cokie.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/scripts/metronic.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/admin/layout/scripts/layout.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/admin/layout/scripts/demo.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/admin/pages/scripts/login.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/global/plugins/bootbox/bootbox.min.js') }}" type="text/javascript"></script>
            <script src="{{ asset('assets/admin/pages/scripts/ui-alert-dialog-api.js') }}"></script>
            <script>
               jQuery(document).ready(function() {     
                  Metronic.init(); // init metronic core components
                  Layout.init(); // init current layout
                  Login.init();
                  Demo.init();
                  UIAlertDialogApi.init();
               });
            </script>
         </body>
      </html>
