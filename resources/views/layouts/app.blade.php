<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Muserpol - @yield('title') </title>
   
    <link rel="stylesheet" href="{!! asset('css/vendor.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/app.css') !!}" />
    
    <style>
        .toast-title {
            font-weight: 700
        }

        .toast-message {
            -ms-word-wrap: break-word;
            word-wrap: break-word
        }

        .toast-message a, .toast-message label {
            color: #fff
        }

        .toast-message a:hover {
            color: #ccc;
            text-decoration: none
        }

        .toast-close-button {
            position: relative;
            right: -.3em;
            top: -.3em;
            float: right;
            font-size: 20px;
            font-weight: 700;
            color: #fff;
            -webkit-text-shadow: 0 1px 0 #fff;
            text-shadow: 0 1px 0 #fff;
            opacity: .8;
            -ms-filter: alpha(Opacity=80);
            filter: alpha(opacity=80)
        }

        .toast-close-button:focus, .toast-close-button:hover {
            color: #000;
            text-decoration: none;
            cursor: pointer;
            opacity: .4;
            -ms-filter: alpha(Opacity=40);
            filter: alpha(opacity=40)
        }

        button.toast-close-button {
            padding: 0;
            cursor: pointer;
            background: 0 0;
            border: 0;
            -webkit-appearance: none
        }

        .toast-top-center {
            top: 0;
            right: 0;
            width: 100%
        }

        .toast-bottom-center {
            bottom: 0;
            right: 0;
            width: 100%
        }

        .toast-top-full-width {
            top: 0;
            right: 0;
            width: 100%
        }

        .toast-bottom-full-width {
            bottom: 0;
            right: 0;
            width: 100%
        }

        .toast-top-left {
            top: 12px;
            left: 12px
        }

        .toast-top-right {
            top: 12px;
            right: 12px
        }

        .toast-bottom-right {
            right: 12px;
            bottom: 12px
        }

        .toast-bottom-left {
            bottom: 12px;
            left: 12px
        }

        #toast-container {
            position: fixed;
            z-index: 999999
        }

        #toast-container * {
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box
        }

        #toast-container > div {
            position: relative;
            overflow: hidden;
            margin: 0 0 6px;
            padding: 15px 15px 15px 50px;
            width: 300px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            background-position: 15px center;
            background-repeat: no-repeat;
            -moz-box-shadow: 0 0 12px #999;
            -webkit-box-shadow: 0 0 12px #999;
            box-shadow: 0 0 12px #999;
            color: #fff;
            opacity: .8;
            -ms-filter: alpha(Opacity=80);
            filter: alpha(opacity=80)
        }

        #toast-container > :hover {
            -moz-box-shadow: 0 0 12px #000;
            -webkit-box-shadow: 0 0 12px #000;
            box-shadow: 0 0 12px #000;
            opacity: 1;
            -ms-filter: alpha(Opacity=100);
            filter: alpha(opacity=100);
            cursor: pointer
        }

    

        #toast-container.toast-bottom-center > div, #toast-container.toast-top-center > div {
            width: 300px;
            margin: auto
        }

        #toast-container.toast-bottom-full-width > div, #toast-container.toast-top-full-width > div {
            width: 96%;
            margin: auto
        }

        /*.toast {
            background-color: #030303
        }

        .toast-success {
            background-color: #51a351
        }

        .toast-error {
            background-color: #bd362f
        }

        .toast-info {
            background-color: #2f96b4
        }

        .toast-warning {
            background-color: #f89406
        }
*/
        .toast-progress {
            position: absolute;
            left: 0;
            bottom: 0;
            height: 4px;
            background-color: #000;
            opacity: .4;
            -ms-filter: alpha(Opacity=40);
            filter: alpha(opacity=40)
        }

        @media all and (max-width: 240px) {
            #toast-container > div {
                padding: 8px 8px 8px 50px;
                width: 11em
            }

            #toast-container .toast-close-button {
                right: -.2em;
                top: -.2em
            }
        }

        @media all and (min-width: 241px) and (max-width: 480px) {
            #toast-container > div {
                padding: 8px 8px 8px 50px;
                width: 18em
            }

            #toast-container .toast-close-button {
                right: -.2em;
                top: -.2em
            }
        }

        @media all and (min-width: 481px) and (max-width: 768px) {
            #toast-container > div {
                padding: 15px 15px 15px 50px;
                width: 25em
            }
        }
    </style>
    <style type="text/css">
        /*
     *  Usage:
     *
          <div class="sk-folding-cube">
            <div class="sk-cube1 sk-cube"></div>
            <div class="sk-cube2 sk-cube"></div>
            <div class="sk-cube4 sk-cube"></div>
            <div class="sk-cube3 sk-cube"></div>
          </div>
     *
     */
    .sk-folding-cube {
      margin: 40px auto;
      width: 40px;
      height: 40px;
      position: relative;
      -webkit-transform: rotateZ(45deg);
              transform: rotateZ(45deg); }
      .sk-folding-cube .sk-cube {
        float: left;
        width: 50%;
        height: 50%;
        position: relative;
        -webkit-transform: scale(1.1);
            -ms-transform: scale(1.1);
                transform: scale(1.1); }
      .sk-folding-cube .sk-cube:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #1ab394;
        -webkit-animation: sk-foldCubeAngle 2.4s infinite linear both;
                animation: sk-foldCubeAngle 2.4s infinite linear both;
        -webkit-transform-origin: 100% 100%;
            -ms-transform-origin: 100% 100%;
                transform-origin: 100% 100%; }
      .sk-folding-cube .sk-cube2 {
        -webkit-transform: scale(1.1) rotateZ(90deg);
                transform: scale(1.1) rotateZ(90deg); }
      .sk-folding-cube .sk-cube3 {
        -webkit-transform: scale(1.1) rotateZ(180deg);
                transform: scale(1.1) rotateZ(180deg); }
      .sk-folding-cube .sk-cube4 {
        -webkit-transform: scale(1.1) rotateZ(270deg);
                transform: scale(1.1) rotateZ(270deg); }
      .sk-folding-cube .sk-cube2:before {
        -webkit-animation-delay: 0.3s;
                animation-delay: 0.3s; }
      .sk-folding-cube .sk-cube3:before {
        -webkit-animation-delay: 0.6s;
                animation-delay: 0.6s; }
      .sk-folding-cube .sk-cube4:before {
        -webkit-animation-delay: 0.9s;
                animation-delay: 0.9s; }
    @-webkit-keyframes sk-foldCubeAngle {
      0%, 10% {
        -webkit-transform: perspective(140px) rotateX(-180deg);
                transform: perspective(140px) rotateX(-180deg);
        opacity: 0; }
      25%, 75% {
        -webkit-transform: perspective(140px) rotateX(0deg);
                transform: perspective(140px) rotateX(0deg);
        opacity: 1; }
      90%, 100% {
        -webkit-transform: perspective(140px) rotateY(180deg);
                transform: perspective(140px) rotateY(180deg);
        opacity: 0; } }
    @keyframes sk-foldCubeAngle {
      0%, 10% {
        -webkit-transform: perspective(140px) rotateX(-180deg);
                transform: perspective(140px) rotateX(-180deg);
        opacity: 0; }
      25%, 75% {
        -webkit-transform: perspective(140px) rotateX(0deg);
                transform: perspective(140px) rotateX(0deg);
        opacity: 1; }
      90%, 100% {
        -webkit-transform: perspective(140px) rotateY(180deg);
                transform: perspective(140px) rotateY(180deg);
        opacity: 0; } }
            
        .sk-fading-circle {
          margin: 100px auto;
          width: 40px;
          height: 40px;
          position: relative;
        }

        .sk-fading-circle .sk-circle {
          width: 100%;
          height: 100%;
          position: absolute;
          left: 0;
          top: 0;
        }

        .sk-fading-circle .sk-circle:before {
          content: '';
          display: block;
          margin: 0 auto;
          width: 15%;
          height: 15%;
          background-color: #333;
          border-radius: 100%;
          -webkit-animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
                  animation: sk-circleFadeDelay 1.2s infinite ease-in-out both;
        }
        .sk-fading-circle .sk-circle2 {
          -webkit-transform: rotate(30deg);
              -ms-transform: rotate(30deg);
                  transform: rotate(30deg);
        }
        .sk-fading-circle .sk-circle3 {
          -webkit-transform: rotate(60deg);
              -ms-transform: rotate(60deg);
                  transform: rotate(60deg);
        }
        .sk-fading-circle .sk-circle4 {
          -webkit-transform: rotate(90deg);
              -ms-transform: rotate(90deg);
                  transform: rotate(90deg);
        }
        .sk-fading-circle .sk-circle5 {
          -webkit-transform: rotate(120deg);
              -ms-transform: rotate(120deg);
                  transform: rotate(120deg);
        }
        .sk-fading-circle .sk-circle6 {
          -webkit-transform: rotate(150deg);
              -ms-transform: rotate(150deg);
                  transform: rotate(150deg);
        }
        .sk-fading-circle .sk-circle7 {
          -webkit-transform: rotate(180deg);
              -ms-transform: rotate(180deg);
                  transform: rotate(180deg);
        }
        .sk-fading-circle .sk-circle8 {
          -webkit-transform: rotate(210deg);
              -ms-transform: rotate(210deg);
                  transform: rotate(210deg);
        }
        .sk-fading-circle .sk-circle9 {
          -webkit-transform: rotate(240deg);
              -ms-transform: rotate(240deg);
                  transform: rotate(240deg);
        }
        .sk-fading-circle .sk-circle10 {
          -webkit-transform: rotate(270deg);
              -ms-transform: rotate(270deg);
                  transform: rotate(270deg);
        }
        .sk-fading-circle .sk-circle11 {
          -webkit-transform: rotate(300deg);
              -ms-transform: rotate(300deg);
                  transform: rotate(300deg); 
        }
        .sk-fading-circle .sk-circle12 {
          -webkit-transform: rotate(330deg);
              -ms-transform: rotate(330deg);
                  transform: rotate(330deg); 
        }
        .sk-fading-circle .sk-circle2:before {
          -webkit-animation-delay: -1.1s;
                  animation-delay: -1.1s; 
        }
        .sk-fading-circle .sk-circle3:before {
          -webkit-animation-delay: -1s;
                  animation-delay: -1s; 
        }
        .sk-fading-circle .sk-circle4:before {
          -webkit-animation-delay: -0.9s;
                  animation-delay: -0.9s; 
        }
        .sk-fading-circle .sk-circle5:before {
          -webkit-animation-delay: -0.8s;
                  animation-delay: -0.8s; 
        }
        .sk-fading-circle .sk-circle6:before {
          -webkit-animation-delay: -0.7s;
                  animation-delay: -0.7s; 
        }
        .sk-fading-circle .sk-circle7:before {
          -webkit-animation-delay: -0.6s;
                  animation-delay: -0.6s; 
        }
        .sk-fading-circle .sk-circle8:before {
          -webkit-animation-delay: -0.5s;
                  animation-delay: -0.5s; 
        }
        .sk-fading-circle .sk-circle9:before {
          -webkit-animation-delay: -0.4s;
                  animation-delay: -0.4s;
        }
        .sk-fading-circle .sk-circle10:before {
          -webkit-animation-delay: -0.3s;
                  animation-delay: -0.3s;
        }
        .sk-fading-circle .sk-circle11:before {
          -webkit-animation-delay: -0.2s;
                  animation-delay: -0.2s;
        }
        .sk-fading-circle .sk-circle12:before {
          -webkit-animation-delay: -0.1s;
                  animation-delay: -0.1s;
        }

        @-webkit-keyframes sk-circleFadeDelay {
          0%, 39%, 100% { opacity: 0; }
          40% { opacity: 1; }
        }

        @keyframes sk-circleFadeDelay {
          0%, 39%, 100% { opacity: 0; }
          40% { opacity: 1; } 
        }
        .panel > .sk-folding-cube {
          display: none;
        }
        .panel.sk-loading {
          position: relative;
        }
        .panel.sk-loading:after {
          content: '';
          background-color: rgba(255, 255, 255, 0.8);
          position: absolute;
          top: 0;
          left: 0;
          right: 0;
          bottom: 0;
        }
        .panel.sk-loading > .panel-body > .sk-folding-cube {
          display: block;
          position: absolute;
          top: 40%;
          left: 0;
          right: 0;
          z-index: 2000;
        }
        .panel.sk-loading > .panel-body > .sk-fading-circle {
          display: block;
          position: absolute;
          top: 40%;
          left: 0;
          right: 0;
          z-index: 2000;
        }
      </style>
</head>
<body class="md-skin fixed-nav no-skin-config">

  <!-- Wrapper-->
    <div id="wrapper">

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page wraper -->
        <div id="page-wrapper" class="gray-bg">

            <!-- Page wrapper -->
            @include('layouts.topnavbar')

            <!-- Main view  -->
            <div id="app">
                
            @yield('content')
            <flash message="{{ session('flash') }}"></flash>
            </div>
            <!-- Footer -->
            @include('layouts.footer')

        </div>
        <!-- End page wrapper-->

    </div>
    <!-- End wrapper-->

<script src="{!! asset('js/app.js') !!}" type="text/javascript"></script>


@section('scripts')
@show

</body>
</html>
