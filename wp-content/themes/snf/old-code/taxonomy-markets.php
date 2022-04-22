<div class="row mt-3 mb-3">
        <div class="col-md-3 col-sm-12  d-flex market-tab-list" style="padding-left:0">
            <?php
            $term_list = get_terms( array(
                'taxonomy' => 'markets',
                'hide_empty' => 'false',
                'orderby' => 'meta_value_num',
                'meta_key' => 'order_number',
                "fields" => "all",
            ) );?>
            <!-- Tabs nav -->
            <div class="list-group" id="list-tab" role="tablist">
                <?php $count = 0;?>
                <?php foreach($term_list as $term_single): $count ++; ?>
                    <a class="list-group-item list-group-item-action <?php if($count == 1) { ?> active<?php } ?>" href="#home-<?php echo $term_single->slug ?>-tab" data-toggle="list"  role="tab" aria-controls="home"><?php echo $term_single->name ?></a>
                <?php endforeach;?>
            </div>
        </div>
        <div class="col-md-9 col-sm-12 d-flex">
            <!-- Tabs content -->
            <div class="tab-content" id="v-pills-tabContent">
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
                    <?php if($term_single->description != null):?>
                        <div role="tabpanel" class="shadow tab-pane <?php if($count == 1) { ?> active<?php } ?>" id="home-<?php echo $term_single->slug ?>-tab">
                            <?php $cat_image = get_field('hero_image', 'category_'.$term_single->term_id); ?>
                            <div class="card  flex-fill">
                                <img src="<?php echo $cat_image['url']; ?>" alt="SNF serves many markets and <?php echo $term_single->name;?> is just one" class="img-fluid"  />
                                <div class="card-body">
                                    <h4 class="font-italic mb-4"><?php echo $term_single->name ?></h4>
                                    <p class="font-italic text-muted mb-2"><?php echo $term_single->description;?></p>
                                    <?php $marketRedirectURL = get_field('page_url', 'category_'.$term_single->term_id); ?>
                                    <?php if(is_page('markets')):?>
                                        <a class="btn btn-outline-primary" href="<?php echo $marketRedirectURL ;?>">View Products </a>
                                        <a class="btn btn-outline-primary" href="<?php echo home_url('/');?>contact-us">Contact Us</a>
                                    <?php else:?>
                                        <a class="btn btn-outline-primary" href="<?php echo $marketRedirectURL ;?>">View Market Page</a>
                                        <a class="btn btn-outline-primary" href="<?php echo $marketRedirectURL ;?>products">View Market Products</a>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                    <?php else:?>
                    <?php endif;?>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</div>