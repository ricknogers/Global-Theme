<div class="main_container">
	<div class="migrate-information">
		<h3>Migration Process:</h3>
		<ul class="pda-migratetext">
			<li>- Update file protection logic</li>
			<li>- Update protected files' information on database</li>
			<li>- Improve file access permission</li>
			<li>- Optimize htacces rewrite rules & file protection performance</li>
			<li>- Add new requested features and <a href="https://preventdirectaccess.com/wordpress-prevent-direct-access-to-media-3-0/" target="_blank">many more</a></li>
		</ul>
	</div>
	<form id="migration-form">
        <?php wp_nonce_field( 'pda_ajax_nonce_v3', 'nonce_pda_v3' ) ?>
		<!-- style="display: none" -->
		<div id="migration-progress" class="">
			<div id="migration-progress-bar" class=""></div>
		</div>
		<p class="pda-migratebutton">
			<input type="submit" class="button button-primary btnmigrate" id="submit" value="Migrate data">
		</p>
	</form>
</div>