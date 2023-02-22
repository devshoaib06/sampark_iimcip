<!DOCTYPE html>
<html lang="en-us">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" shrink-to-fit="no">
  <title>IIM Calcutta | Innovation park </title>
  <link rel="icon" href="{{ asset('public/front_end/images/favicon.png') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('public/incubation/css/bootstrap.min.css') }}" />
  <link rel="stylesheet" href="{{ asset('public/incubation/css/style.css') }}" />
  {{-- <link rel="stylesheet" href="{{ asset('public/incubation/css/style1.css') }}" /> --}}
  <link rel="stylesheet" href="{{ asset('public/incubation/css/responsive.css') }}" />
  <link rel="stylesheet" href="{{ asset('public/incubation/css/navigation.css') }}" />
  <link rel="stylesheet" href="{{ asset('public/assets/bower_components/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{asset('public/front_end/fancybox/source/jquery.fancybox.css?v=2.1.7')}}"
    type="text/css" media="screen" />
  <link rel="stylesheet"
    href="{{asset('public/front_end/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5')}}" type="text/css"
    media="screen" />
  <link rel="stylesheet" href="{{asset('public/front_end/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7')}}"
    type="text/css" media="screen" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js">
  </script>

  <link rel="stylesheet" type="text/css" href="{{ asset('public/css/incubatee_style.css') }}">

  <style type="text/css">
    .roy-vali-error {
      color: red;
    }

    /*.search-desktop #autocomplete { width: 400px !important; }
    .search-desktop ul.dropdown-menu { width: 439px !important; left: 0px !important; }*/
    .search-desktop li a.dropdown-item {
      word-wrap: break-word !important;
      white-space: normal !important;
    }

    a.link-nouder:hover {
      text-decoration: none;
    }
  </style>
  @stack('page_css')
</head>

<body>