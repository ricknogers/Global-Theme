<section class="login-block shadow">
    <div class="container login-container register">
        <div class="row">
            <div class="col-md-6 login-sec">
                <div class=" register-right white ">
                    <div class="row">
                        <div class="col-sm-12">
                            <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
	                            <li class="nav-item">
                                    <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="true">Register</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="login-tab" data-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="false">Login</a>
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
            <div class="col-md-6 banner-sec">
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
                            <img class="d-block img-fluid" src="https://images.pexels.com/photos/872957/pexels-photo-872957.jpeg" alt="Third slide">
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
    </div>
</section>
