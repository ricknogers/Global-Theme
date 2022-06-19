<div class="container login-container container-noChildPages">
    <div class="row">
        <div class="login-reg-panel">
            <div class="login-info-box">
                <h2>Have an account?</h2>
                <p>To access the Investor Center, please sign-in with credentials provided upon registration</p>
                <hr>
                <label id="label-register" for="log-reg-show">Login</label>
                <input type="radio" name="active-log-panel" id="log-reg-show"  checked="checked">
            </div>

            <div class="register-info-box">
                <h2>Don't have an account?</h2>
                <p>For current SNF Investors, Bankers, etc. with questions or are
                    having difficulty logging into the SNF Investor Center, please contact:</p>
                <p><b><a href="mailto:snfinvestorrelations@snf.com">snfinvestorrelations@snf.com</a></b></p>
                <hr>
                <label id="label-login" for="log-login-show">Register</label>
                <input type="radio" name="active-log-panel" id="log-login-show">
            </div>

            <div class="white-panel">
                <div class="login-show">
                    <h2>LOGIN</h2>
                    <hr>
                    <?php wp_login_form();?>
                </div>
                <div class="register-show">
                    <h2>Investor Registration</h2>
                    <p>Please complete the fields below to request access to Investor Center documents</p>
                    <hr>
                    <?php gravity_form( 1, false, false, false, '', false ); ?>

                </div>
            </div>
        </div>
    </div>
</div>