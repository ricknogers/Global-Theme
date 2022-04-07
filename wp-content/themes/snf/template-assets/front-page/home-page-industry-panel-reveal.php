 <div class="container-fluid">
    <!-- ACCORDION ROW -->
    <div class="row row-cols-12 " id="hp_market_display">
        <?php
        $term_list = get_terms( array(
            'taxonomy' => 'markets',
            'hide_empty' => 'false',
            'orderby' => 'meta_value_num',
            'meta_key' => 'order_number',

            "fields" => "all",
        ) );?>
        <?php $count = 0;?>
        <?php foreach($term_list as $term_single): $count ++; ?>
            <?php if($term_single->parent > 0):?>
                <?php $marketColorSelection = get_field('color_scheme', 'category_'.$term_single->term_id); ?>
                <?php $cat_image = get_field('hero_image', 'category_'.$term_single->term_id); ?>
                <?php $termLink = get_field('page_url', 'category_'.$term_single->term_id); ?>
                <div class="col  market-icon mt-3 mb-3 "  >
                    <?php $marketIcon = get_field('market_icon', 'category_'.$term_single->term_id); ?>
                    <a class="" href="<?php echo $termLink ;?>">
                        <div class="market-title  h-100"style="background:<?php echo $marketColorSelection ;?>">
                            <div class="icon-square-hover">
                                <img src="<?php echo $marketIcon['url'];?>" class="img-fluid" />
                            </div>
                            <div class="card-container">
                                <h3 class="card-title"><?php echo $term_single->name ?></h3>
                            </div>

                        </div>
                        <div class="market-icon-container h-100" style="background:<?php echo $marketColorSelection ;?>">
                            <div class="market-title"  style="background-image: url(<?php echo $cat_image['url'];?>)">
                                <div class="card-container">
                                    <p class="lead"><?php echo $term_single->description; ?></p>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endif;?>
        <?php endforeach;?>
    </div>
</div>