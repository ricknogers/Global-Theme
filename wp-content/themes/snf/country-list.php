<div class="locations-modal button">
    <a class="btn-open btn-open-modal" href="#locationsModal" data-toggle="modal"><span><i class="bi bi-globe"></i></span></a>
</div>
<div class="modal modal-fullscreen-lg fade" id="locationsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <a href="<?php echo home_url('/');?>" class="navbar-brand">
                    <img src="<?php bloginfo('template_directory'); ?>/resources/images/logos/SNF-Water-Science-Dark-blue-SVG.svg" alt="SNF Logo" class="img-fluid mx-auto d-block">

                </a>
                <h5 class="modal-title">Select Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="input-results-country-terms col-sm-12">
                    <div class="row" role="tabpanel">
                        <div class="col-md-6 col-sm-12">
                            <div class="list-group" id="myList" role="tablist">
                                <?php $counter = 0;?>
                                <a class="list-group-item list-group-item-action p-3  <?php if ($counter === 0):?> active <?php endif;?> " data-toggle="list" href="#all" role="tab" aria-controls="all">All Locations</a>
                                <?php foreach( get_terms( 'country', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) :?>
                                    <?php // display top level term name ;?>
                                    <a class="list-group-item list-group-item-action p-2" data-toggle="list" href="#<?php echo $parent_term->slug; ?>" role="tab" aria-controls="<?php echo $parent_term->slug; ?>"><?php echo $parent_term->name; ?></a>
                                    <?php $counter++; ?>
                                <?php endforeach;?>
                            </div><!--list-group-->
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <div class="tab-content">
                                <?php $counterLoop = 0;?>
                                <div class="tab-pane <?php if ($counterLoop === 0):?> active <?php endif;?>" id="all" aria-labelledby="all" role="tabpanel">
                                    <h6 class="text-muted">Other Options</h6>
                                    <ul class="list-group list-group-flush">
                                        <a class="p-3 list-group-item d-flex justify-content-between" href="<?php echo home_url('/');?>contact-us/">Contact Us</a>
                                        <a class="p-3 list-group-item d-flex justify-content-between" href="<?php echo home_url('/');?>products/">Products</a>
                                        <a class="p-3 list-group-item d-flex justify-content-between" href="<?php echo home_url('/');?>investor-center/">Investors</a>
                                        <a class="p-3 list-group-item d-flex justify-content-between" href="<?php echo home_url('/');?>investor-center/">SNF Sustainability</a>
                                    </ul>
                                </div>
                                <?php foreach( get_terms( 'country', array( 'hide_empty' => false, 'parent' => 0 ) ) as $parent_term ) :?>
                                    <div class="tab-pane " id="<?php echo $parent_term->slug; ?>" role="tabpanel">
                                        <h6 class="text-muted"><?php echo $parent_term->name; ?></h6>
                                        <ul class="list-group list-group-flush">
                                        <?php foreach( get_terms( 'country', array( 'hide_empty' => false, 'parent' => $parent_term->term_id ) ) as $child_term ) :?>
                                            <?php $link = get_field('country_page_redirect', 'category_'.$child_term->term_id); ?>
                                            <?php if($link):?>
                                                <li class="list-group-item">
                                                    <a href="<?php echo $link['url'];?>">
                                                        <?php echo $child_term->name;?>
                                                    </a>
                                                </li>
                                            <?php else:?>
		                                        <?php  $url = get_term_link($child_term->slug, 'country');?>
                                                <li class="list-group-item">

                                                    <a href="<?php echo $url['url'];?>">
                                                       <?php echo $child_term->name;?>
                                                    </a>
                                                </li>
                                            <?php endif;?>
                                        <?php endforeach;?>
                                        </ul>
                                        <?php $counterLoop++; ?>
                                    </div><!--tab-pane-->
                                <?php endforeach;?>
                            </div><!--tab -content -->
                        </div>
                    </div>


                </div>
            </div><!--modal-body-->
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">&times;</button>
            </div>
        </div>
    </div>
</div>