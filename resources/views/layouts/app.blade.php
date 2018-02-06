<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Muserpol - @yield('title') </title>
   
    <link rel="stylesheet" href="{!! asset('css/vendor.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/app.css') !!}" />
    @section('styles')
    @show
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

<script>
    !function(e){e(["jquery"],function(e){return function(){function t(e,t,n){return f({type:O.error,iconClass:g().iconClasses.error,message:e,optionsOverride:n,title:t})}function n(t,n){return t||(t=g()),v=e("#"+t.containerId),v.length?v:(n&&(v=c(t)),v)}function i(e,t,n){return f({type:O.info,iconClass:g().iconClasses.info,message:e,optionsOverride:n,title:t})}function o(e){w=e}function s(e,t,n){return f({type:O.success,iconClass:g().iconClasses.success,message:e,optionsOverride:n,title:t})}function a(e,t,n){return f({type:O.warning,iconClass:g().iconClasses.warning,message:e,optionsOverride:n,title:t})}function r(e){var t=g();v||n(t),l(e,t)||u(t)}function d(t){var i=g();return v||n(i),t&&0===e(":focus",t).length?void h(t):void(v.children().length&&v.remove())}function u(t){for(var n=v.children(),i=n.length-1;i>=0;i--)l(e(n[i]),t)}function l(t,n){return t&&0===e(":focus",t).length?(t[n.hideMethod]({duration:n.hideDuration,easing:n.hideEasing,complete:function(){h(t)}}),!0):!1}function c(t){return v=e("<div/>").attr("id",t.containerId).addClass(t.positionClass).attr("aria-live","polite").attr("role","alert"),v.appendTo(e(t.target)),v}function p(){return{tapToDismiss:!0,toastClass:"toast",containerId:"toast-container",debug:!1,showMethod:"fadeIn",showDuration:300,showEasing:"swing",onShown:void 0,hideMethod:"fadeOut",hideDuration:1e3,hideEasing:"swing",onHidden:void 0,extendedTimeOut:1e3,iconClasses:{error:"toast-error",info:"toast-info",success:"toast-success",warning:"toast-warning"},iconClass:"toast-info",positionClass:"toast-top-right",timeOut:5e3,titleClass:"toast-title",messageClass:"toast-message",target:"body",closeHtml:'<button type="button">&times;</button>',newestOnTop:!0,preventDuplicates:!1,progressBar:!1}}function m(e){w&&w(e)}function f(t){function i(t){return!e(":focus",l).length||t?(clearTimeout(O.intervalId),l[r.hideMethod]({duration:r.hideDuration,easing:r.hideEasing,complete:function(){h(l),r.onHidden&&"hidden"!==b.state&&r.onHidden(),b.state="hidden",b.endTime=new Date,m(b)}})):void 0}function o(){(r.timeOut>0||r.extendedTimeOut>0)&&(u=setTimeout(i,r.extendedTimeOut),O.maxHideTime=parseFloat(r.extendedTimeOut),O.hideEta=(new Date).getTime()+O.maxHideTime)}function s(){clearTimeout(u),O.hideEta=0,l.stop(!0,!0)[r.showMethod]({duration:r.showDuration,easing:r.showEasing})}function a(){var e=(O.hideEta-(new Date).getTime())/O.maxHideTime*100;f.width(e+"%")}var r=g(),d=t.iconClass||r.iconClass;if("undefined"!=typeof t.optionsOverride&&(r=e.extend(r,t.optionsOverride),d=t.optionsOverride.iconClass||d),r.preventDuplicates){if(t.message===C)return;C=t.message}T++,v=n(r,!0);var u=null,l=e("<div/>"),c=e("<div/>"),p=e("<div/>"),f=e("<div/>"),w=e(r.closeHtml),O={intervalId:null,hideEta:null,maxHideTime:null},b={toastId:T,state:"visible",startTime:new Date,options:r,map:t};return t.iconClass&&l.addClass(r.toastClass).addClass(d),t.title&&(c.append(t.title).addClass(r.titleClass),l.append(c)),t.message&&(p.append(t.message).addClass(r.messageClass),l.append(p)),r.closeButton&&(w.addClass("toast-close-button").attr("role","button"),l.prepend(w)),r.progressBar&&(f.addClass("toast-progress"),l.prepend(f)),l.hide(),r.newestOnTop?v.prepend(l):v.append(l),l[r.showMethod]({duration:r.showDuration,easing:r.showEasing,complete:r.onShown}),r.timeOut>0&&(u=setTimeout(i,r.timeOut),O.maxHideTime=parseFloat(r.timeOut),O.hideEta=(new Date).getTime()+O.maxHideTime,r.progressBar&&(O.intervalId=setInterval(a,10))),l.hover(s,o),!r.onclick&&r.tapToDismiss&&l.click(i),r.closeButton&&w&&w.click(function(e){e.stopPropagation?e.stopPropagation():void 0!==e.cancelBubble&&e.cancelBubble!==!0&&(e.cancelBubble=!0),i(!0)}),r.onclick&&l.click(function(){r.onclick(),i()}),m(b),r.debug&&console&&console.log(b),l}function g(){return e.extend({},p(),b.options)}function h(e){v||(v=n()),e.is(":visible")||(e.remove(),e=null,0===v.children().length&&(v.remove(),C=void 0))}var v,w,C,T=0,O={error:"error",info:"info",success:"success",warning:"warning"},b={clear:r,remove:d,error:t,getContainer:n,info:i,options:{},subscribe:o,success:s,version:"2.1.0",warning:a};return b}()})}("function"==typeof define&&define.amd?define:function(e,t){"undefined"!=typeof module&&module.exports?module.exports=t(require("jquery")):window.toastr=t(window.jQuery)});
    //# sourceMappingURL=/toastr.js.map
</script>
@section('scripts')
@show

</body>
</html>
