<!DOCTYPE html>
<html lang="{{session()->get('locale')}}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- SEO Meta Tags -->
    <meta name="title" content="{{isset(json_decode(get_settings('site_setting'))->meta_title)? json_decode(get_settings('site_setting'))->meta_title:''}}">
    <meta name="description" content="{{isset(json_decode(get_settings('site_setting'))->meta_description)? json_decode(get_settings('site_setting'))->meta_description:''}}">
    <meta name="keywords" content="bulk sms, sms marketing, schedule sms, signalwire, sms , sms gateway, telnyx, twilio"/>
    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
    <meta property="og:site_name" content=""/> <!-- website name -->
    <meta property="og:site" content=""/> <!-- website link -->
    <meta property="og:title" content=""/> <!-- title shown in the actual shared post -->
    <meta property="og:description" content=""/> <!-- description shown in the actual shared post -->
    <meta property="og:image" content=""/> <!-- image link, make sure it's jpg -->
    <meta property="og:url" content=""/> <!-- where do you want your post to link to -->
    <meta property="og:type" content="article"/>

    <!-- Website Title -->
    <title>PicoQR - {{trans('layout.terms_conditions')}}</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext"
          rel="stylesheet">
    <link href="{{asset('front/css/bootstrap.css')}}" rel="stylesheet">
    @if (isset(json_decode(get_settings('site_setting'))->cookie_consent) && json_decode(get_settings('site_setting'))->cookie_consent == 'enable')
        <link rel="stylesheet" type="text/css"
              href="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.css"/>
    @endif
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <link href="{{asset('front/css/swiper.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/magnific-popup.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/styles.css')}}" rel="stylesheet">

    <!-- Favicon  -->
    <link rel="icon" href="{{asset('uploads/'.json_decode(get_settings('site_setting'))->favicon)}}">
</head>
<style>
    .header .header-content {
        padding-top: 0rem !important;
    }
</style>
<body data-spy="scroll" data-target=".fixed-top">

<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <div class="container">

        <!-- Image Logo -->
        <a class="navbar-brand logo-image" href="{{route('index')}}"><img
                src="{{asset('uploads/'.json_decode(get_settings('site_setting'))->logo)}}" alt="QR"></a>

        <!-- Mobile Menu Toggle Button -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-awesome fas fa-bars"></span>
            <span class="navbar-toggler-awesome fas fa-times"></span>
        </button>
        <!-- end of mobile menu toggle button -->

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#header">{{trans('layout.home')}} <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#details">{{trans('layout.demo')}}</a>
                </li>


                <li class="nav-item">
                    <a class="nav-link page-scroll" href="#pricing">{{trans('layout.pricing')}}</a>
                </li>
            </ul>
            <span class="nav-item">
                    <a target="_blank" class="btn-outline-sm" href="{{route('login')}}">{{trans('layout.login')}}</a>
                </span>
        </div>
    </div> <!-- end of container -->
</nav> <!-- end of navbar -->
<!-- end of navigation -->


<!-- Header -->
<header id="header" class="header">
    <div class="header-content"></div>
</header> <!-- end of header -->


<div class="cards-1 mt-5">
    <div class="container mt-5 pt-2">
        <div class="row">
            <div class="col-lg-12">
                <div class="above-heading">---------------</div>
                <h2 class="h2-heading">{{trans('layout.terms_conditions')}}</h2>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
        <div class="row" style="min-height: 400px">
            <div class="col-lg-12">
                {{isset(json_decode(get_settings('site_setting'))->terms_conditions)? json_decode(get_settings('site_setting'))->terms_conditions:''}}
            </div>
            <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div>

<!-- Footer -->
<svg class="footer-frame" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
     viewBox="0 0 1920 79">
    <defs>
        <style>.cls-2 {
                fill: #5f4def;
            }</style>
    </defs>
    <title>{{trans('layout.footer_frame')}}</title>
    <path class="cls-2"
          d="M0,72.427C143,12.138,255.5,4.577,328.644,7.943c147.721,6.8,183.881,60.242,320.83,53.737,143-6.793,167.826-68.128,293-60.9,109.095,6.3,115.68,54.364,225.251,57.319,113.58,3.064,138.8-47.711,251.189-41.8,104.012,5.474,109.713,50.4,197.369,46.572,89.549-3.91,124.375-52.563,227.622-50.155A338.646,338.646,0,0,1,1920,23.467V79.75H0V72.427Z"
          transform="translate(0 -0.188)"/>
</svg>
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-col first">
                    <h4>{{trans('layout.about')}} {{isset(json_decode(get_settings('site_setting'))->name)? json_decode(get_settings('site_setting'))->name:''}}</h4>
                    <p class="p-small">{{trans('layout.lorem_ipsum_dolor_sit_amet_consectetur_adipiscing_elit_mauris')}}</p>
                </div>
            </div> <!-- end of col -->
            <div class="col-md-4">
                <div class="footer-col middle">
                    <h4>{{trans('layout.important_links')}}</h4>
                    <ul class="list-unstyled li-space-lg p-small">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">{{trans('layout.our_business_partners')}} <a class="white" href="#">{{trans('layout.qr_picotech_app')}}</a>
                            </div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">{{trans('layout.read_our')}} <a class="white" href="#">{{trans('layout.terms_conditions')}}</a>, <a
                                    class="white" href="#">{{trans('layout.privacy_policy')}}</a></div>
                        </li>
                    </ul>
                </div>
            </div> <!-- end of col -->
            <div class="col-md-4">
                <div class="footer-col last">
                    <h4>{{trans('layout.contact')}}</h4>
                    <ul class="list-unstyled li-space-lg p-small">
                        <li class="media">
                            <i class="fas fa-map-marker-alt"></i>
                            <div class="media-body">{{trans('layout.modhure_more_kurigram')}}</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-envelope"></i>
                            <div class="media-body"><a class="white" href="#">{{trans('layout.picotech_com')}}</a> <i
                                    class="fas fa-globe"></i><a class="white" href="#your-link">{{trans('layout.qr_picotech_app')}}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of footer -->


<!-- Copyright -->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="p-small">{{trans('layout.copyright_footer')}} © {{date('Y')}}</p>
            </div> <!-- end of col -->
        </div> <!-- enf of row -->
    </div> <!-- end of container -->
</div> <!-- end of copyright -->
<!-- end of copyright -->


<!-- Scripts -->
<script src="{{asset('front/js/jquery.min.js')}}"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
<script src="{{asset('front/js/popper.min.js')}}"></script> <!-- Popper tooltip library for Bootstrap -->
<script src="{{asset('front/js/bootstrap.min.js')}}"></script> <!-- Bootstrap framework -->
<script src="{{asset('front/js/jquery.easing.min.js')}}"></script>
<!-- jQuery Easing for smooth scrolling between anchors -->
<script src="{{asset('front/js/swiper.min.js')}}"></script> <!-- Swiper for image and text sliders -->
<script src="{{asset('front/js/jquery.magnific-popup.js')}}"></script> <!-- Magnific Popup for lightboxes -->
<script src="{{asset('front/js/scripts.js')}}"></script> <!-- Custom scripts -->
@if (isset(json_decode(get_settings('site_setting'))->cookie_consent) && json_decode(get_settings('site_setting'))->cookie_consent == 'enable')
    <script src="{{asset('js/cookie_consent.js')}}"></script>
    <script>
        window.cookieconsent.initialise({
            "palette": {
                "popup": {
                    "background": "#8000ff"
                },
                "button": {
                    "background": "#f2cbcb"
                }
            },
            "theme": "classic",
            "position": "bottom-left",
            "type": "opt-out",
            "content": {
                "href": "{{route('privacy.policy')}}"
            }
        });
    </script>
@endif

@if(isset(json_decode(get_settings('site_setting'))->crips_token) && json_decode(get_settings('site_setting'))->crips_token)
    <script type="text/javascript">window.$crisp = [];
        window.CRISP_WEBSITE_ID = "{{json_decode(get_settings('site_setting'))->crips_token}}";
        (function () {
            d = document;
            s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            d.getElementsByTagName("head")[0].appendChild(s);
        })();</script>
@endif
</body>
</html>
