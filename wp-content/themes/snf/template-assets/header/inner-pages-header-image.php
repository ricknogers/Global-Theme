<div class="container">

	<?php if(get_field('page_header_type') == 'default'):?>

		<?php get_template_part('template-assets/header/user-selection/default-selection');?>
           <?php if(is_search()):?>
			<?php get_template_part('template-assets/header/user-selection/search-header');?>
		<?php endif;?>
	<?php endif;?>

	<?php if(get_field('page_header_type') == 'single'):?>

		<?php get_template_part('template-assets/header/user-selection/single-selection');?>

	<?php endif;?>

	<?php if(get_field('page_header_type') == 'optimized'):?>

		<?php get_template_part('template-assets/header/user-selection/optimized-selection');?>

	<?php endif;?>

	<?php if(get_field('page_header_type') == 'sustainability'):?>

		<?php get_template_part('template-assets/header/user-selection/sustainability-selection');?>

	<?php endif;?>

	<?php if(get_field('page_header_type') == 'industry-header'):?>

		<?php get_template_part('template-assets/header/user-selection/industry-selection');?>

	<?php endif;?>

	<?php if(get_field('page_header_type') == 'country'):?>

		<?php get_template_part('template-assets/header/user-selection/country-selection');?>

	<?php endif;?>
	<?php if(get_field('page_header_type') == 'investor'):?>

		<?php get_template_part('template-assets/header/user-selection/investor-selection');?>

	<?php endif;?>





</div>


