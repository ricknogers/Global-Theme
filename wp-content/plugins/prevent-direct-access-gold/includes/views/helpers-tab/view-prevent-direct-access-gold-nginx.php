<?php $rules = Prevent_Direct_Access_Gold_Htaccess::get_nginx_rules(); ?>
<p>
	Update our rewrite rules on your NGINX server <a target="_blank" rel="noopener noreferrer"
	                                                  href="https://preventdirectaccess.com/docs/nginx-support/">as per
		this instruction</a>
</p>
<p>
<textarea class="code" readonly="readonly" cols="90"
          rows="<?php echo count( $rules ); ?>"><?php echo esc_textarea( implode( "\n", $rules ) ); ?></textarea>
</p>
