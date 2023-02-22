<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" shrink-to-fit="no">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/front_end/css/bootstrap.min.css') }}" />
    <title>Layout | IIM Calcutta Innovation park </title>
    <style>
        body {
            background-color: #e5eef5;
        }
        /*.img-cont-wrap {
            width: 100%;
            height: auto;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }*/
        
        .img-cont-wrap {
            width: 100%;
            height: auto;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            border: 10px solid #e5eef5;
        }
        
        .img-cont-wrap img {
            width: auto;
            display: block;
            margin: 0 auto;
        }
        
        .img-cont-wrap h3 {
            font-size: 20px;
            font-weight: 600;
            margin-top: 10px;
            margin-bottom: 0px;
        }
        
        .img-cont-wrap a h3 {
            color: #3b3b3b;
        }
        
        .img-cont-wrap h5 {
            font-size: 16px;
            font-weight: 400;
            margin-top: 5px;
            margin-bottom: 0px;
        }
        
        .img-cont-wrap a h5 {
            color: #848080;
        }
        
        .img-cont-wrap a:hover {
            text-decoration: none;
        }
    </style>
</head>

<body>
    <main>
        <div class="layout-wrap">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="img-cont-wrap">
                            <a href="#"> <img src="{{ asset('public/all_site/logox.png') }}" class="img-fluid">
                                <h3>Dummy Heading</h3>
                                <h5>This is a sub heading</h5>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>


<footer>
    <script src="{{ asset('public/front_end/js/jquery.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/popper.min.js') }}"></script>
    <script src="{{ asset('public/front_end/js/bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var windowHeight = $(window).height();
            var layoutHeight = windowHeight;
            var wrapHeight = (windowHeight / 2) + 'px';
            var imgHeight = (windowHeight / 2) - 50 + 'px';
            $('.img-cont-wrap').css({
                'height': wrapHeight,
                'padding': '25px'
            });
            $('.img-cont-wrap img').css({
                'height': auto,
                'width': 'auto'
            });
            $('.layout-wrap').css({
                'height': layoutHeight
            });

        })
    </script>

</footer>

</body>