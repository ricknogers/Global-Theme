<aside class=" list-group archiveSideBar">
    <section class="sidebar-widget">
        <div class="heading mb-2 "><h4 class="display-5">Sustainability</h4></div>
	    <?php $esg_pdf_link = get_option('snf_esg_update_link');?>
	    <?php $esg_title = get_option('snf_esg_link_title');?>
        <ul class="list-group">
	        <?php if ( $esg_pdf_link ) : ?>
                <li class="list-group-item">
                    <a target="_blank" href="<?php echo $esg_pdf_link;?>">
                        <h5>Environmental & Social Responsibility Report</h5>
                        <img src="https://snf.com/wp-content/uploads/2022/04/esg_2022.png" class="img-fluid" />
                    </a>
                </li>
	        <?php endif; ?>
            <li class="list-group-item">
		        <?php  $code_conduct = get_option('snf_code_of_conduct') ;?>
                <a target="_blank" href="<?php echo $code_conduct;?>">
                    <h5>Code of Conduct</h5>
                    <img src="https://snf.com/wp-content/uploads/2022/03/SNF-Code-of-Conduct-Cover-scaled-1.jpg" class="img-fluid" />
                </a>
            </li>
        </ul>
        <a class="btn btn-outline-primary btn-default btn-block my-3" href="<?php echo home_url('/');?>investors/">Back to Investors</a>
    </section>
</aside><!--newsSideBar-->