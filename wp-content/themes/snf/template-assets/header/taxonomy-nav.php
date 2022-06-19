<?php if(is_tree('80431')):?> <?php // if page is decendent of /industry page ;?>
	<?php $terms = get_the_terms(get_the_ID(), 'markets');?>
    <div class=" " id="custom-tax-second-nav" style="<?php foreach($terms as $term) :?><?php $breadcrumbsColor = get_field('color_scheme', $term->taxonomy . '_' . $term->term_id);?>background-color:<?php echo $breadcrumbsColor;?><?php endforeach; ?>">
        <div class="col second-tier-nav subsidiary-children-pages  h-100 bg-transparent">
            <div class="page-listing-positioning ">
	            <?php if( $terms ): ?>
                    <?php foreach($terms as $term) :?>
                        <?php if($term->slug == 'oil-gas'):?>
                            <?php snf_markets_og_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
			            <?php if($term->slug == 'municipal-water-treatment'):?>
				            <?php snf_markets_municipal_nav(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
                        <?php if($term->slug == 'personal-care'):?>
                            <?php snf_markets_pc_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'agriculture'):?>
                            <?php snf_markets_ag_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'construction'):?>
                            <?php snf_markets_construction_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'dredging'):?>
                            <?php snf_markets_dredging_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'equipment-engineering'):?>
                            <?php snf_markets_equipment_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'home-care-ii'):?>
                            <?php snf_markets_homecare_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'industrial-water-treatment'):?>
                            <?php snf_markets_industrial_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'mining'):?>
                            <?php snf_markets_mining_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>

                        <?php if($term->slug == 'pulp-paper'):?>
                            <?php snf_markets_pulp_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
                        <?php if($term->slug == 'textiles'):?>
                            <?php snf_markets_textiles_nav(0); ?><!--/.navbar-collapse -->
                        <?php endif;?>
		            <?php endforeach; ?>
	            <?php endif;?>


            </div>
        </div>
    </div>
<?php endif;?>
<?php if( is_tree('102') && !(is_page('locations'))  ):?>
    <div class=" " id="custom-tax-second-nav">

        <div class="col second-tier-nav subsidiary-children-pages  h-100 bg-transparent">
            <div class="page-listing-positioning  "  >

            </div>
        </div>
    </div>
<?php endif;?>