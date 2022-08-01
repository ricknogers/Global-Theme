


<section class="login-block shadow-sm">
    <div class="container login-container register mb-4">
        <div class="row">
            <div class="col page-header theme-bg-dark py-md-5 text-center position- optimized-selection  ">
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
        <div class="row my-md-4 h-100">
            <div class="col-sm-12 text-center ">
                <h2 class="display-4" style="font-size:1.7rem;">   Interested in becoming an investor with us?</h2>
            </div>

            <div class="col-md-6 col-sm-12 investor_core_values  inv_reg_form  my-md-3 my-2">
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
            <div class="col-md-6 col-sm-12 investor_core_values inv_login_form my-md-3 my-2 ">
                <section class="text-center">
                    <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#investorLogin">
                        Already an investor? Login Here
                    </button>
                </section>
                <div class="modal right fade" id="investorLogin" tabindex="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="investor-login-form ">
									<?php wp_login_form(); ?>


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
    <div class="container d-none d-md-block icon_figure_grouping">
        <div class="row my-md-4 my-sm-2 ">
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
                            <h3 class="investor_title">400K END USERS</h3>
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
                        <div class="investor_image"><i class="fa fa-balance-scale" aria-hidden="true"></i></div>
                        <div class="investor_info">
                            <h3 class="investor_title">1325 KT/Y</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container slider-highlights ">
        <div class="row">
            <div class="col-md-12 banner-sec investors_slideshow">
                <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner" role="listbox">
                        <div class="carousel-item active">
                            <div class="inv bg-img" style="background-image:url(https://snf.com/wp-content/uploads/2021/10/Industrial-Waste-Treatment.jpg); "></div>

                            <div class="carousel-caption-inv d-block">
                                <div class="banner-text">

                                    <p>70 subsidiaries located in more than 40 countries, in 3 major economic regions (The Americas, Europe, and Asia)</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="inv bg-img" style="background-image:url(<?php bloginfo('template_directory'); ?>/resources/images/snf-agriculture.jpg); "></div>

                            <div class="carousel-caption-inv d-block">
                                <div class="banner-text">
                                    <p>With 400 Scientists/Laboratory Technicians, 230 Field Technicians, and 100 Engineers; R&D, Engineering, and Innovation are at the Heart of our Strategy.</p>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="inv bg-img" style="background-image:url(https://snf.com/wp-content/uploads/2022/05/Reclaimed-Tailings-Pond-scaled-1.jpg); "></div>

                            <div class="carousel-caption-inv  d-block">
                                <div class="banner-text">
                                    <p>SNF, founded in 1978, has developed a strategy of organic growth and reinvestment that could not be achieved by a publicly traded corporation. SNF is committed to continuously maintaining it’s privately held status.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row no-gutters investor_counting">
            <div class="col-sm-12 inv-title-seperation">
                <section class="inv_title_container">
                    <div class="heading "><h4 class="display-5">Discover More Investor Resources</h4></div>
                </section>
            </div>
            <div class="col-sm-12 my-md-3">
                <div class="row  investor_advancement mb-3 ">
                    <div class="col-md-6 col-sm-12 investor_core_values my-3 ">
                        <div class="card text-white bg-dark  h-100 rounded" style="background-color:#0082CA">
                            <div class="card-body">
                                <h5 class="card-title">Mission</h5>
                                <p class="card-text">Assure our clients the most reliable source and the broadest range of water-soluble polymers and services and help them reduce their water and energy needs and their carbon footprint</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 investor_core_values my-3">
                        <div class="card text-white bg-dark   h-100 rounded" style="background-color:#0082CA">
                            <div class="card-body">
                                <h5 class="card-title">Vision</h5>
                                <p class="card-text">Be the world's preeminent provider of sustainable water chemistry</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 graphSeperator mt-3 mb-3 d-sm-none ">
                <div class="row">
                    <div class="col-sm-12 inv-title-seperation">
                        <section class="inv_title_container">
                            <div class="heading "><h4 class="display-5">SNF Market Share by Industry</h4></div>
                        </section>
                    </div>
                </div>
                <div class="row " >
                    <div class="col-md-2 single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue4">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-7" stroke-dasharray="44, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Water Treatment</text>
                            <text x="18" y="20.35" class="percentage  ">44&#37;</text>
                        </svg>
                    </div>

                    <div class="col-md-2 single-chart">
                        <svg  viewBox="0 0 36 36" class="circular-chart blue2 ">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-28" stroke-dasharray="20, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Oil & Gas</text>
                            <text x="18" y="20.35" class="percentage ">20%</text>
                        </svg>
                    </div>
                    <div class="col-md-2  single-chart">
                        <svg  viewBox="0 0 36 36" class="circular-chart blue3">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                            <path class="circle circle-11" stroke-dasharray="12, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Mining</text>
                            <text x="18" y="20.35" class="percentage  ">12%</text>
                        </svg>
                    </div>
                    <div class="col-md-2  single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue4">
                            <path class="circle-bg" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-7" stroke-dasharray="10, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Paper</text>
                            <text x="18" y="20.35" class="percentage  ">10%</text>
                        </svg>
                    </div>
                    <div class="col-md-2  single-chart">
                        <svg viewBox="0 0 36 36" class="circular-chart blue5">
                            <path class="circle-bg" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <path class="circle circle-7" stroke-dasharray="9, 100" d="M18 2.0845a 15.9155 15.9155 0 0 1 0 31.831a 15.9155 15.9155 0 0 1 0 -31.831"/>
                            <text x="18" y="13.35" class="chartTitle">Other</text>
                            <text x="18" y="20.35" class="percentage  ">9%</text>
                        </svg>
                    </div>
                    <div class="col-md-2  single-chart">
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
                    <div class=" col-sm-12  hover-mask ">
                        <a class="hover_icon " href="https://snf.com/wp-content/uploads/2022/07/Key-Figures-Web-2022-V2.png" >
                            <img class="d-block img-fluid" loading="lazy" src="https://snf.com/wp-content/uploads/2022/07/Key-Figures-Web-2022-V2.png" alt=" SNF Global Key Figures our impact on a global scale">

                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>


</section>
