<?php
$element = apply_filters( PDA_v3_Constants::PDA_V3_BEFORE_RENDER_PDA_COLUMN, false, $post_id );
if ( $element ) {
	return $element;
}

$repo              = new PDA_v3_Gold_Repository;
$is_protected_file = $repo->is_protected_file( $post_id );
$pda_class         = $is_protected_file ? '' : PDA_v3_Constants::PDA_V3_CLASS_FOR_FILE_UNPROTECTED;
$pda_text          = $is_protected_file ? PDA_v3_Constants::PDA_V3_FILE_PROTECTED : PDA_v3_Constants::PDA_V3_FILE_UNPROTECTED;
$title_text        = $is_protected_file ? PDA_v3_Constants::PDA_V3_TITLE_FOR_FILE_PROTECTED : PDA_v3_Constants::PDA_V3_TITLE_FOR_FILE_UNPROTECTED;
$pda_icon          = $is_protected_file ? '<i class="fa fa-check-circle" aria-hidden="true"></i>&nbsp;' : '<i class="fa fa-times-circle" aria-hidden="true"></i>&nbsp;';
$gold_function     = new Pda_Gold_Functions();
?>
<div id="pda-v3-column_<?php echo esc_attr( $post_id ); ?>" class="pda-gold-v3-tools">
	<p id="pda-v3-wrap-status_<?php echo esc_attr( $post_id ); ?>">
		<span id="pda-v3-text_<?php echo esc_attr( $post_id ); ?>"
		      class="protection-status <?php echo esc_attr( $pda_class ); ?>"
		      title="<?php echo esc_attr( $title_text ); ?>">
            <?php
            echo $pda_icon . esc_html( $pda_text );
            ?>
        </span>
		<?php do_action( PDA_Private_Hooks::PDA_HOOK_SHOW_STATUS_FILE_IN_PDA_COLUMN, $post_id ); ?>
	</p>
	<?php if ( $gold_function->pda_check_role_protection() ) { ?>
	<div>
		<a class="pda_gold_btn"
		   id="pda_gold-<?php echo $post_id ?>"><?php echo esc_html__( 'Configure file protection', 'prevent-direct-access-gold' ) ?></a>
		<?php } ?>
	</div>
</div>
