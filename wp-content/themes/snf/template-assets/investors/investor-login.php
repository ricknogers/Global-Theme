<section class="login-block shadow-sm">
    <div class=" login-container register">
        <div class="row">
            <div class="col page-header theme-bg-dark py-5 text-center position- optimized-selection mt-3 ">
                <div class="page-header-shapes-right "></div>
                <div class="page-header-shapes-left"></div>
                <div class="row">
                    <div class="col page-header-title" >
                        <h1><?php the_title();?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col breadcrumbs-container optimized-selection">
                <div class="card shadow p-1">
                    <div class="card-body shadow-sm snf-breadcrumbs ">
                        <div class="col  crumbs">
						    <?php if (function_exists('rank_math_the_breadcrumbs')) rank_math_the_breadcrumbs(); ?>
                        </div>

                    </div><!--card-body shadow-sm snf-breadcrumbs-->
                </div><!--card shadow-->
            </div><!--breadcrumbs-container-->
        </div><!--row-->
        <div class="row  mb-4 mt-4  h-100">
            <div class="col-sm-12 text-center mb-5">
                <h3 class="display-4" style="font-size:1.7rem;">   Interested in becoming an investor with us?</h3>
            </div>
            <div class="col-md-6 col-sm-12 investor_core_values  inv_reg_form">
                <section class="text-center">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#investorRegistrationForm">
                        Become an Investor
                    </button>
                </section>
                <div class="modal left fade" id="investorRegistrationForm" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h3  class="register-heading"></h3>
                                <div class="row register-form">
                                    <div class="col-md-12">
                                        <p>Please complete the fields below to request access to Investor Center documents</p>
                                        <hr class="diamond">
                                        <?php gravity_form( 1, false, false, false, '', false ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 investor_core_values inv_login_form ">
                <section class="text-center">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#investorLogin">
                        Already an investor? Login Here
                    </button>
                </section>
                <div class="modal right fade" id="investorLogin" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="investor-login-form form-control">
	                                <?php
	                                $redirect_to = '';
	                                ?>
                                    <form name="loginform" id="loginform" action="<?php echo site_url( '/wp-login.php' ); ?>" method="post">
                                        <div class="row">
                                            <div class="col">
                                                <input id="user_login" placeholder="name@example.com" class="form-control" type="text" size="20" value="" name="log">
                                                <input id="user_pass" class="form-control" type="password" size="20" value="" name="pwd">
                                                <p><input id="rememberme" type="checkbox" value="forever" name="rememberme"></p>

                                                <p><input id="wp-submit" type="submit" value="Login" name="wp-submit"></p>

                                                <input type="hidden" value="<?php echo esc_attr( $redirect_to ); ?>" name="redirect_to">
                                                <input type="hidden" value="1" name="testcookie">

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row mb-4 mt-4">
            <div class="col-md-6 col-sm-12">
                <div class="investor_figure box">
                    <div class="investor_icon">
                        <div class="investor_image"><i class="bi bi-currency-euro rounded-circle"></i></div>
                        <div class="investor_info">
                            <h3 class="investor_title">3.6B SALES TURNOVER</h3>

                        </div>
                    </div>
                    <div class="space"></div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="investor_figure box">
                    <div class="investor_icon">
                        <div class="investor_image"><i class="fa fa-users rounded-circle" aria-hidden="true"></i></div>
                        <div class="investor_info">
                            <h3 class="investor_title">400K END USER</h3>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="investor_figure box">
                    <div class="investor_icon">
                        <div class="investor_image"><i class="bi bi-currency-exchange"></i></div>
                        <div class="investor_info">
                            <h3 class="investor_title">1.4% R&D INVESTMENT FROM SALES</h3>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="investor_figure box">
                    <div class="investor_icon">
                        <div class="investor_image"><i class="bi bi-power"></i></div>
                        <div class="investor_info">
                            <h3 class="investor_title">1325 KT/Y</h3>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container login-container register">
        <div class="row">

            <div class="col-md-12 banner-sec investors_slideshow">

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
