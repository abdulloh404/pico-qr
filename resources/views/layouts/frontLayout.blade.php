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
    <title>{{isset(json_decode(get_settings('site_setting'))->name)? json_decode(get_settings('site_setting'))->name:''}} - {{trans('layout.a_simple_restaurant_menu_maker')}}</title>

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

@php $template = json_decode(get_settings('frontend_template')); @endphp
<!-- Header -->
<header id="header" class="header">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-xl-5">
                    <div class="text-container">
                        <h1>{{isset($template->name)?$template->name:''}}</h1>
                        <p class="p-large">{{isset($template->title)?$template->title:''}}</p>
                        <a target="_blank" class="btn-solid-lg page-scroll"
                           href="{{route('registration')}}">{{trans('layout.signup')}}</a>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6 col-xl-7">
                    <div class="image-container">
                        <div class="img-wrapper">
                            @if(isset($template->bg_image_file_name))
                                <img class="img-fluid" src="{{asset('uploads/'.$template->bg_image_file_name)}}" alt="">
                            @else
                                <img class="img-fluid" src="" alt="">
                            @endif
                        </div> <!-- end of img-wrapper -->
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of header-content -->
</header> <!-- end of header -->
<svg class="header-frame" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none"
     viewBox="0 0 1920 310">
    <defs>
        <style>.cls-1 {
                fill: #5f4def;
            }</style>
    </defs>
    <title>header-frame</title>
    <path class="cls-1"
          d="M0,283.054c22.75,12.98,53.1,15.2,70.635,14.808,92.115-2.077,238.3-79.9,354.895-79.938,59.97-.019,106.17,18.059,141.58,34,47.778,21.511,47.778,21.511,90,38.938,28.418,11.731,85.344,26.169,152.992,17.971,68.127-8.255,115.933-34.963,166.492-67.393,37.467-24.032,148.6-112.008,171.753-127.963,27.951-19.26,87.771-81.155,180.71-89.341,72.016-6.343,105.479,12.388,157.434,35.467,69.73,30.976,168.93,92.28,256.514,89.405,100.992-3.315,140.276-41.7,177-64.9V0.24H0V283.054Z"/>
</svg>
<br><br> <br><br>
<!-- end of header -->


<!-- Description -->
<div class="cards-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="above-heading">---------------</div>
                <h2 class="h2-heading">{{isset($template->main_title)?$template->main_title:''}}</h2>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
        <div class="row">
            <div class="col-lg-12">

                <!-- Card -->
                <div class="card">
                    <div class="card-image">
                        @if(isset($template->first_img_file_name))
                            <img class="img-fluid" src="{{asset('uploads/'.$template->first_img_file_name)}}" alt="">
                        @else
                            <img class="img-fluid" src="" alt="">
                        @endif
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">{{isset($template->first_title)?$template->first_title:''}}</h4>
                        <p>{{isset($template->first_description)?$template->first_description:''}}</p>
                    </div>
                </div>
                <!-- end of card -->

                <!-- Card -->
                <div class="card">
                    <div class="card-image">
                        @if(isset($template->sec_img_file_name))
                            <img class="img-fluid" src="{{asset('uploads/'.$template->sec_img_file_name)}}" alt="">
                        @else
                            <img class="img-fluid" src="" alt="">
                        @endif
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">{{isset($template->sec_title)?$template->sec_title:''}}</h4>
                        <p>{{isset($template->sec_description)?$template->sec_description:''}}</p>
                    </div>
                </div>
                <!-- end of card -->

                <!-- Card -->
                <div class="card">
                    <div class="card-image">
                        @if(isset($template->thr_img_file_name))
                            <img class="img-fluid" src="{{asset('uploads/'.$template->thr_img_file_name)}}" alt="">
                        @else
                            <img class="img-fluid" src="" alt="">
                        @endif
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">{{isset($template->thr_title)?$template->thr_title:''}}</h4>
                        <p>{{isset($template->thr_description)?$template->thr_description:''}}</p>
                    </div>
                </div>
                <!-- end of card -->

            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of cards-1 -->
<!-- end of description -->


<!-- Demo -->
<div id="details" class="basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>{{isset($template->section_three_title)?$template->section_three_title:''}}</h2>
                    <p>{{isset($template->section_three_description)?$template->section_three_description:''}}</p>
                    <a class="btn-solid-reg page-scroll" href="{{route('registration')}}">{{trans('layout.signup')}}</a>
                </div> <!-- end of text-container -->
            </div> <!-- end of col -->
            <div class="col-lg-6">
                <div class="image-container">
                    @if(isset($template->section_three_bg_image_file_name))
                        <img class="img-fluid" src="{{asset('uploads/'.$template->section_three_bg_image_file_name)}}" alt="">
                    @else
                        <img class="img-fluid" src="" alt="">
                    @endif
                </div> <!-- end of image-container -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of basic-1 -->
<!-- end of demo -->


<!-- Pricing -->
<div id="pricing" class="cards-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="h2-heading">{{isset($template->section_four_title)?$template->section_four_title:''}}</h2>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
        <div class="row">
            <div class="col-lg-12">
            @if(isset($plans))

                @foreach($plans as $plan)
                <!-- Card-->
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">{{$plan->title}}</div>
                        <div class="price"><span class="value">{{formatNumberWithCurrSymbol($plan->cost)}}</span></div>
                        <div class="frequency">{{$plan->recurring_type}}</div>
                        <div class="divider"></div>
                        <ul class="list-unstyled li-space-lg">
                            <li class="media">
                                <i class="fas fa-check"></i>
                                <div class="media-body">
                                    @if($plan->item_unlimited=='yes')
                                        {{trans('layout.unlimited_items')}}
                                    @else
                                        <strong>{{$plan->item_limit}}</strong>
                                        {{trans('layout.item_limit')}}
                                    @endif
                                </div>
                            </li>
                            <li class="media">
                                <i class="fas fa-check"></i>
                                <div class="media-body">
                                    @if($plan->table_unlimited=='yes')
                                        {{trans('layout.unlimited_tables')}}
                                    @else
                                        <strong>{{$plan->table_limit}}</strong>
                                        {{trans('layout.table_limit')}}
                                    @endif
                                </div>
                            </li>
                            <li class="media">
                                <i class="fas fa-check"></i>
                                <div class="media-body">
                                    @if($plan->restaurant_unlimited=='yes')
                                        {{trans('layout.unlimited_restaurants')}}
                                    @else
                                        <strong>{{$plan->restaurant_limit}}</strong>
                                        {{trans('layout.restaurant_limit')}}
                                    @endif
                                </div>
                            </li>
                            <li class="media">
                                <i class="fas fa-check"></i>
                                <div class="media-body">
                                    {{trans('layout.unlimited_support')}}
                                </div>
                            </li>

                        </ul>
                        <div class="button-wrapper">
                            <a class="btn-solid-reg page-scroll" href="{{route('registration',['plan'=>$plan->id])}}">{{trans('layout.join')}}</a>
                        </div>
                    </div>
                </div> <!-- end of card -->
                <!-- end of card -->
                @endforeach
                @endif
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of cards-2 -->
<!-- end of pricing -->


<!-- Testimonials -->
<div class="slider-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                <!-- Text Slider -->
                <div class="slider-container">
                    <div class="swiper-container text-slider">
                        <div class="swiper-wrapper">

                            <!-- Slide -->
                            <div class="swiper-slide">
                                <div class="image-wrapper">
                                    @if(isset($template->first_comment_img_file_name))
                                        <img class="img-fluid" src="{{asset('uploads/'.$template->first_comment_img_file_name)}}" alt="">
                                    @else
                                        <img class="img-fluid" src="" alt="">
                                    @endif
                                </div> <!-- end of image-wrapper -->
                                <div class="text-wrapper">
                                    <div class="testimonial-text">{{isset($template->first_comment)?$template->first_comment:''}}</div>
                                    <div class="testimonial-author">{{isset($template->first_name)?$template->first_name:''}}</div>
                                </div> <!-- end of text-wrapper -->
                            </div> <!-- end of swiper-slide -->
                            <!-- end of slide -->

                            <!-- Slide -->
                            <div class="swiper-slide">
                                <div class="image-wrapper">
                                    @if(isset($template->sec_comment_img_file_name))
                                        <img class="img-fluid" src="{{asset('uploads/'.$template->sec_comment_img_file_name)}}" alt="">
                                    @else
                                        <img class="img-fluid" src="" alt="">
                                    @endif
                                </div> <!-- end of image-wrapper -->
                                <div class="text-wrapper">
                                    <div class="testimonial-text">{{isset($template->sec_comment)?$template->sec_comment:''}}</div>
                                    <div class="testimonial-author">{{isset($template->sec_name)?$template->sec_name:''}}</div>
                                </div> <!-- end of text-wrapper -->
                            </div> <!-- end of swiper-slide -->
                            <!-- end of slide -->

                            <!-- Slide -->
                            <div class="swiper-slide">
                                <div class="image-wrapper">
                                    @if(isset($template->thr_comment_img_file_name))
                                        <img class="img-fluid" src="{{asset('uploads/'.$template->thr_comment_img_file_name)}}" alt="">
                                    @else
                                        <img class="img-fluid" src="" alt="">
                                    @endif
                                </div> <!-- end of image-wrapper -->
                                <div class="text-wrapper">
                                    <div class="testimonial-text">{{isset($template->thr_comment)?$template->thr_comment:''}}</div>
                                    <div class="testimonial-author">{{isset($template->thr_name)?$template->thr_name:''}}</div>
                                </div> <!-- end of text-wrapper -->
                            </div> <!-- end of swiper-slide -->
                            <!-- end of slide -->

                        </div> <!-- end of swiper-wrapper -->

                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <!-- end of add arrows -->

                    </div> <!-- end of swiper-container -->
                </div> <!-- end of slider-container -->
                <!-- end of text slider -->

            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of slider-2 -->
<!-- end of testimonials -->


<!-- Newsletter -->
<div class="form">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-container">
                    <div class="above-heading">NEWSLETTER</div>
                    <h2>{{isset($template->section_five_title)?$template->section_five_title:''}}</h2>

                    <!-- Newsletter Form -->
                    <form method="post" action="{{route('subscribe')}}" id="newsletterForm" data-toggle="validator" data-focus="false">
                      @csrf
                        <div class="form-group">
                            <input name="email" type="email" class="form-control-input" id="nemail" required>
                            <label class="label-control" for="nemail">{{trans('layout.email')}}</label>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group checkbox">
                            <input type="checkbox" id="nterms" value="Agreed-to-Terms" required>{{trans('layout.i_have_read_and_agree_to')}}
                            {{isset(json_decode(get_settings('site_setting'))->name)? json_decode(get_settings('site_setting'))->name:''}}'{{trans('layout.s_written')}} <a href="{{route('privacy.policy')}}}">{{trans('layout.privacy_policy')}}</a>{{trans('layout.and')}}  <a
                                href="{{route('terms.conditions')}}">{{trans('layout.terms_conditions')}}</a>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control-submit-button">{{trans('layout.subscribe')}}</button>
                        </div>
                        <div class="form-message">
                            <div id="nmsgSubmit" class="h3 text-center hidden"></div>
                        </div>
                    </form>
                    <!-- end of newsletter form -->

                </div> <!-- end of text-container -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="icon-container">
                        <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                    <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                    <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-pinterest-p fa-stack-1x"></i>
                            </a>
                        </span>
                    <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                    <span class="fa-stack">
                            <a href="#your-link">
                                <i class="fas fa-circle fa-stack-2x"></i>
                                <i class="fab fa-linkedin-in fa-stack-1x"></i>
                            </a>
                        </span>
                </div> <!-- end of col -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of form -->
<!-- end of newsletter -->


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
                            <div class="media-body"><a class="white" href="#">{{trans('layout.picotech_com ')}}</a> <i
                                    class="fas fa-globe"></i><a class="white" href="#your-link">{{trans('layout.qr_picotech_app')}}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of footer -->
<!-- end of footer -->


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
