<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" shrink-to-fit="no">
    <title>IIM Calcutta | Innovation park </title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/front_end/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/assets/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/front_end/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/front_end/css/responsive.css') }}" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="/resources/demos/style.css">
    <style>
        body {
            position: static;
            background: url('images/bg-img.png') no-repeat bottom right, #e5eef5;
        }
        .roy-vali-error { color: red; }
        #signup_step2, #signup_step3, #signup_step4 {
            display: none;
        }
        .select2-container--default .select2-selection--multiple {
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.1);
            -webkit-border-radius: 2px;
            border-radius: 2px;
            cursor: text;
            height: 42px;
        }
        .select2-container--default .select2-search--inline .select2-search__field{
            width:initial !important;
        }
    </style>
    @stack('page_css')
</head>
<body>