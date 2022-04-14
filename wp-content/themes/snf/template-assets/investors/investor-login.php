<section class="login-block shadow-sm">
    <div class="container login-container register">
        <div class="row">
            <div class="col-md-6 login-sec">
                <div class=" register-right white ">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
	                            <li class="nav-item">
                                    <a class="nav-link active" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="true">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="false">Login</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="row full-height justify-content-center">
                        <div class="col-sm-12">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
                                   <h3  class="register-heading">Investor Registration</h3>
                                    <div class="row register-form">
                                        <div class="col-md-12">
                                            <p>Please complete the fields below to request access to Investor Center documents</p>
                                            <hr class="diamond">
                                            <?php gravity_form( 1, false, false, false, '', false ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade show" id="login" role="tabpanel" aria-labelledby="login-tab">
	                                 <h3 class="register-heading">Login</h3>
                                    <div class="row register-form">
                                        <div class="col-md-12">
                                            <div class="investor-login-form">
		                                        <?php
		                                        $args = array(
			                                        'redirect' => home_url('/investors/'),

		                                        )
		                                        ;?>
                                                <?php wp_login_form($args);?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 banner-sec investors_slideshow">
                <div class="row">
                    <div class="col-md-6">
                        <a href="#">
                            <div class="card service-card card-inverse h-100">
                                <div class="card-block">
                                    <i class="bi bi-currency-euro"></i>
                                    <h4 class="card-title">3.6B€ SALES TURNOVER</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#">
                            <div class="card service-card card-inverse h-100">
                                <div class="card-block">
                                    <i class="fa fa-users" aria-hidden="true"></i>
                                    <h4 class="card-title">400K END USERS</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#">
                            <div class="card service-card card-inverse h-100">
                                <div class="card-block">
                                    <span class="fa fa-lightbulb-o fa-3x"></span>
                                    <h4 class="card-title">1.4% R&D INVESTMENT FROM SALES</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="#">
                            <div class="card service-card card-inverse h-100">
                                <div class="card-block">
                                    <span class="fa fa-lightbulb-o fa-3x"></span>
                                    <h4 class="card-title">1325 KT/Y</h4>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <img class="d-block img-fluid" src="https://static.pexels.com/photos/33972/pexels-photo.jpg" alt="First slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>This is First Slide</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid" src="https://images.pexels.com/photos/7097/people-coffee-tea-meeting.jpg" alt="Second slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>This is Second Slide</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <img class="d-block img-fluid" src="https://www.snf.com/wp-content/themes/SNF.COM/images/Key-Figures-Web-2022.png" alt="Third slide">
                            <div class="carousel-caption d-none d-md-block">
                                <div class="banner-text">
                                    <h2>This is Heaven</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters investor_counting">
            <div class="col-sm-12 mb-3 mt-3">
                <h2 class="display-4 text-center">Discover More Investor Resources</h2>
            </div>
            <div class="col-sm-12 mb-3 mt-3">
                <div class="row  investor_advancement mb-3 ">
                    <div class="col-md-6 col-sm-12 investor_core_values  ">
                        <div class="card text-white bg-dark  h-100 rounded" style="background-color:#0082CA">
                            <div class="card-body">
                                <h5 class="card-title">Mission</h5>
                                <p class="card-text">Assure our clients the most reliable source and the broadest range of water-soluble polymers and services and help them reduce their water and energy needs and their carbon footprint</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 investor_core_values ">
                        <div class="card text-white bg-dark   h-100 rounded" style="background-color:#0082CA">
                            <div class="card-body">
                                <h5 class="card-title">Vision</h5>
                                <p class="card-text">Be the world's preeminent provider of sustainable water chemistry</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 graphSeperator mt-3 mb-3">
                <div class="row">
                    <div class="col-sm-12 inv-title-seperation">
                        <section class="inv_title_container">
                            <div class="heading "><h4 class="display-5">SNF Market Share by Industry</h4></div>
                        </section>

                    </div>
                </div>
                <div class="row " >
                    <div class="col-md-2 col-sm-6 col-xs-4 single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue2 ">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-39" stroke-dasharray="44, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Water Treatment</text>
                            <text x="18" y="20.35" class="percentage  ">44&#37;</text>
                        </svg>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-4 single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue2 ">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-28" stroke-dasharray="20, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Oil & Gas</text>
                            <text x="18" y="20.35" class="percentage ">20%</text>
                        </svg>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-4 single-chart">
                        <svg  viewBox="0 0 36 36" class="circular-chart blue3">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="circle circle-11" stroke-dasharray="12, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Mining</text>
                            <text x="18" y="20.35" class="percentage  ">12%</text>
                        </svg>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-4 single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue4">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-7" stroke-dasharray="10, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Paper</text>
                            <text x="18" y="20.35" class="percentage  ">10%</text>
                        </svg>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-4 single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue5">
                            <path class="circle-bg" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-7" stroke-dasharray="9, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Other</text>
                            <text x="18" y="20.35" class="percentage  ">9%</text>
                        </svg>
                    </div>
                    <div class="col-md-2 col-sm-6 col-xs-4 single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue6 ">
                            <path class="circle-bg" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-6" stroke-dasharray="6, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Monomers</text>
                            <text x="18" y="20.35" class="percentage  ">5%</text>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 mt-3 mb-3">
                <div class="row  investor_advancement  ">
                    <div class="col-md-6 col-sm-12  hover-mask ">
                        <a class="hover_icon " href="https://www.snf.com/wp-content/themes/SNF.COM/images/Key-Figures-Web-2022.png" data-toggle="lightbox" data-max-width="600" data-type="image">
                            <img class="d-block img-fluid" src="https://www.snf.com/wp-content/themes/SNF.COM/images/Key-Figures-Web-2022.png" alt="Third slide">
                            <h2><i class="ligthbox fa fa-search-plus" aria-hidden="true"></i></h2>
                        </a>
                    </div>
                    <div class="col-md-6 col-sm-12  ">
                        <div style="padding:56.25% 0 0 0;position:relative;"><iframe src="https://player.vimeo.com/video/384048206?h=573dd206bc&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
