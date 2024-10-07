<!DOCTYPE html>
<html lang="en">
  <head>
    
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>@yield('title')</title>
    <link rel="apple-touch-icon" sizes="57x57" href="/favicon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon-180x180.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192x192.png">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon-144x144.png">
    <meta name="msapplication-config" content="/browserconfig.xml">
    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
    />
    <!-- Google Fonts Roboto -->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap"
    />
    <!-- Regular MDB theme -->
    <link rel="stylesheet" href="/css/mdb.min.css" />
  </head>
  <body data-mdb-theme="dark">
    <header>
    @include('templates.chunks.nav')
    </header>

    <!-- Start your project here-->
    <div class="container my-5">
      @yield('crumbs')

      @if (session('status'))
      <div class="alert alert-info alert-dismissible fade show" role="alert" data-mdb-alert-init="" data-mdb-alert-initialized="true">
        {{ session('status') }}
        <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @if (session('alert'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert" data-mdb-alert-init="" data-mdb-alert-initialized="true">
        {{ session('alert') }}
        <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
      </div>
      @endif

      @yield('content')
    </div>
    <!-- End your project here-->

    <!-- MDB -->
    <script type="text/javascript" src="/js/mdb.umd.min.js"></script>
    <!-- Custom scripts -->
    <script type="text/javascript"></script>
  </body>
</html>
