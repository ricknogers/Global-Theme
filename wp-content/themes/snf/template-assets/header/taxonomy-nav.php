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
	<?php $countryTerms = get_the_terms(get_the_ID(), 'country');?>
    <div class=" " id="custom-tax-second-nav">
        <div class="col second-tier-nav subsidiary-children-pages  h-100 bg-transparent">
            <div class="page-listing-positioning  "  >
	            <?php if( $countryTerms ): ?>
		            <?php foreach($countryTerms as $term) :?>
			            <?php if($term->slug == 'us'):?>
				            <?php snf_country_nav_us(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'fr'):?>
				            <?php snf_country_nav_fr(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'united-kingdom'):?>
				            <?php snf_country_nav_uk(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'canada'):?>
				            <?php snf_country_nav_ca(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'australia'):?>
				            <?php snf_country_nav_australia(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'egypt'):?>
				            <?php snf_country_nav_egypt(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'chad'):?>
				            <?php snf_country_nav_chad(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'israel'):?>
				            <?php snf_country_nav_israel(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'oman'):?>
				            <?php snf_country_nav_oman(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'saudi-arabia'):?>
				            <?php snf_country_nav_saudi_arabia(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'south-africa'):?>
				            <?php snf_country_nav_south_africa(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'united-arab-emirates'):?>
				            <?php snf_country_nav_uae(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'brazil'):?>
				            <?php snf_country_nav_brazil(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'chile'):?>
				            <?php snf_country_nav_chile(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'colombia'):?>
				            <?php snf_country_nav_colombia(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'mexico'):?>
				            <?php snf_country_nav_mexico(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'china'):?>
				            <?php snf_country_nav_china(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'netherlands'):?>
				            <?php snf_country_nav_netherlands(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'in'):?>
				            <?php snf_country_nav_in(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'indonesia'):?>
				            <?php snf_country_nav_indonesia(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'japan'):?>
				            <?php snf_country_nav_japan(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'philippines'):?>
				            <?php snf_country_nav_philippines(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'kr'):?>
				            <?php snf_country_nav_kr(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'singapore'):?>
				            <?php snf_country_nav_singapore(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'taiwan'):?>
				            <?php snf_country_nav_taiwan(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'thailand'):?>
				            <?php snf_country_nav_thailand(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'austria'):?>
				            <?php snf_country_nav_austria(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'belgium'):?>
				            <?php snf_country_nav_belgium(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'croatia'):?>
				            <?php snf_country_nav_croatia(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'czech-republic'):?>
				            <?php snf_country_nav_czech_republic(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'finland'):?>
				            <?php snf_country_nav_finland(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'germany'):?>
				            <?php snf_country_nav_germany(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'greece'):?>
				            <?php snf_country_nav_greece(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'italy'):?>
				            <?php snf_country_nav_italy(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'kazakhstan'):?>
				            <?php snf_country_nav_kazakhstan(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'poland'):?>
				            <?php snf_country_nav_poland(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'portugal'):?>
				            <?php snf_country_nav_portugal(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php //if($term->slug == 'russia'):?>
				            <?php// snf_country_nav_russia(0); ?><!--/.navbar-collapse -->
			            <?php // endif;?>
			         
			            <?php if($term->slug == 'slovakia'):?>
				            <?php snf_country_nav_slovakia(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'spain'):?>
				            <?php snf_country_nav_spain(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'sweden'):?>
				            <?php snf_country_nav_sweden(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'switzerland'):?>
				            <?php snf_country_nav_switzerland(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'turkey'):?>
				            <?php snf_country_nav_turkey(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            <?php if($term->slug == 'argentina'):?>
				            <?php snf_country_nav_argentina(0); ?><!--/.navbar-collapse -->
			            <?php endif;?>
			            
		            <?php endforeach; ?>
	            <?php endif;?>
            </div>
        </div>
    </div>
<?php endif;?>