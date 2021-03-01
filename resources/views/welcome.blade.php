<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="//fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body style="background-color: hsl(210, 60%, 2%);">
        <div id="app">
            @include('layouts.navbar', ["pageData" => [
                "darkmode" => true
            ]])

            <!-- Header -->
            <header class="masthead text-white text-center">
                <div class="overlay"></div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 mx-auto">
                            <h1 class="big-text-h1">Start self-accounting today!</h1>
                        </div>
                        <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                            <div class="col-12 col-md-4 mx-auto">
                                <a role="button" class="btn btn-block btn-lg btn-dark" href="/register">Register now!</a>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Icons Grid -->
            <section class="welcome-bg-change-darkmode features-icons text-center">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                                <div class="features-icons-icon">
                                    <i class="fas fa-exchange-alt"></i>
                                </div>

                                <h3>Monitor your transactions</h3>
                                <p class="lead mb-0">See how much money you earn and spend</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                                <div class="features-icons-icon d-flex justify-content-center">
                                    <i class="fas fa-chart-line"></i>
                                </div>

                                <h3>Track your balance</h3>
                                <p class="lead mb-0">Always be up-to-date with how much money you have</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="features-icons-item mx-auto mb-5 mb-lg-0 mb-lg-3">
                                <div class="features-icons-icon d-flex justify-content-center">
                                    <i class="fas fa-money-check-alt"></i>
                                </div>

                                <h3>Improve your savings</h3>
                                <p class="lead mb-0">Analizing your balance will help you save even more money</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Image Showcases -->
            <section class="showcase-dark">
                <div class="container-fluid p-0">
                    <div class="row no-gutters">
                        <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('https://github.com/StartBootstrap/startbootstrap-landing-page/blob/master/img/bg-showcase-3.jpg?raw=true');"></div>
                        <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                            <h2>Incredibly easy to use</h2>
                            <p class="lead mb-0">
                                The ease of self-accounting is very important to me. That's why I created this website and will continue to improve and expand it in the future!
                            </p>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6 text-white showcase-img" style="background-image: url('https://i.kym-cdn.com/photos/images/newsfeed/001/499/826/2f0.png');"></div>
                        <div class="col-lg-6 my-auto showcase-text">
                            <h2>Get on top of saving money</h2>
                            <p class="lead mb-0">
                                By using this site you will learn how much and what do you spend money on. This will certainly help you with saving money as well as managing your balance better!
                            </p>
                        </div>
                    </div>

                    <div class="row no-gutters">
                        <div class="col-lg-6 order-lg-2 text-white showcase-img" style="background-image: url('https://i.kym-cdn.com/photos/images/newsfeed/001/871/646/471.jpg');"></div>
                        <div class="col-lg-6 order-lg-1 my-auto showcase-text">
                            <h2>Use saved money to invest in yourself</h2>
                            <p class="lead mb-0">
                                New clothes, perfumes, gaming equipment, stocks, a car, or a even house? Anything is possible if you save enough money!
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Testimonials -->
            <section class="welcome-bg-change-darkmode testimonials text-center">
                <div class="container">
                    <h2 class="mb-5">What people are saying...</h2>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                                <img class="img-fluid rounded-circle mb-3" src="https://github.com/StartBootstrap/startbootstrap-landing-page/blob/master/img/testimonials-2.jpg?raw=true" alt="">

                                <h5>Jacob S.</h5>
                                <p class="gold-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </p>
                                <p class="font-weight-light mb-0">"I love it!"</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                                <img class="img-fluid rounded-circle mb-3" src="https://github.com/StartBootstrap/startbootstrap-landing-page/blob/master/img/testimonials-3.jpg?raw=true" alt="">

                                <h5>Abigale N.</h5>
                                <p class="gold-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </p>
                                <p class="font-weight-light mb-0">"Finally a way to improve my self&#8209;accounting&nbsp;abilities!"</p>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="testimonial-item mx-auto mb-5 mb-lg-0">
                                <img class="img-fluid rounded-circle mb-3" src="https://github.com/StartBootstrap/startbootstrap-landing-page/blob/master/img/testimonials-1.jpg?raw=true" alt="">

                                <h5>Margaret E.</h5>
                                <p class="gold-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </p>
                                <p class="font-weight-light mb-0">"This is exactly what I needed!"</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Call to Action -->
            <section class="call-to-action text-white text-center">
                <div class="overlay"></div>
                    <div class="container">
                        <div class="row">
                            <div class="col-xl-9 mx-auto">
                                <h1 class="big-text-h1">What are you waiting for?</h1>
                            </div>
                            <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                                <div class="col-12 col-md-4 mx-auto">
                                    <a role="button" class="btn btn-block btn-lg btn-dark" href="/register">Register now!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        @yield('script')
    </body>
</html>
