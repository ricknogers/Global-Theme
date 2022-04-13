<?php $search=get_search_query(); global $wp_query;global $post;?>
<?php if ( have_posts() && ($search != null || is_search()) || $post->post_content != '' ) :?>
    <div class="page-header theme-bg-dark py-5 text-center position-relative">
        <div class="page-header-shapes-right "></div>
        <div class="page-header-shapes-left"></div>
        <div class="row">
            <div class="col page-header-title" >
                <h1><?php esc_attr_e('404: Page Not Found '); ?></h1>
            </div>
        </div>
    </div>

<?php endif; wp_reset_query()?>