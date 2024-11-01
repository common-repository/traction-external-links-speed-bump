<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="wrap">
	
	<h1> Traction External Links Speed Bump Settings</h1>
	
	<form name="trelsb_settings_form" method="post" action="">							
		<?php wp_nonce_field('trelsb_save_action', 'trelsb_nonce');?>
		<input type="hidden" name="trelsb_form_submitted" value="Y">
		
		<h3>Domain and Link Settings</h3>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="trelsb_ignored_domains">Omitted Domains</label>
					</th>
					<td>
						<input name="trelsb_ignored_domains" id="trelsb_ignored_domains" aria-describedby="omitted-domains-description"type="text" value="<?php if($trelsb_ignored_domains != '' ) {echo $trelsb_ignored_domains; } else { echo $defaults['trelsb_ignored_domains'];} ?>" class="large-text" />
						<p id="omitted-domains-description" class="description"> Enter a comma separated list of domains that will not trigger the speed bump modal.</p>
					</td>
				</tr>
				
				<tr>
					<th scope="row">
						<label for="trelsb_ignored_links">Omitted Links</label>
					</th>
					<td>
						<input name="trelsb_ignored_links" id="trelsb_ignored_links" aria-describedby="omitted-links-description"type="text" value="<?php if($trelsb_ignored_links != '' ) {echo $trelsb_ignored_links; } else { echo $defaults['trelsb_ignored_links'];} ?>" class="large-text" />
						<p id="omitted-links-description" class="description"> Enter a comma separated list of links that will not trigger the speed bump modal.</p>
					</td>
				</tr>	
			</tbody>					
		</table>
		
		<h3>Speed Bump Modal Settings</h3>
		
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="trelsb_speedbump_text">Speed Bump Modal Text</label>
					</th>
					<td>
						<textarea rows="10" name="trelsb_speedbump_text" id="trelsb_speedbump_text" aria-describedby="speedbump-text-description" class="large-text"><?php if($trelsb_speedbump_text != '' ) {echo $trelsb_speedbump_text; } else { echo $defaults['trelsb_speedbump_text'];} ?></textarea>
						<p id="speedbump-text-description" class="description"> Enter the text you want to appear in the speed bump modal when someone clicks an external link. You can use HTMl tags in this area, text without tags around it will automatically receive paragraph tags around it.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="trelsb_continue_text">Continue Button Text</label>
					</th>
					<td>
						<input name="trelsb_continue_text" id="trelsb_continue_text" aria-describedby="continue-text-description"type="text" value="<?php if($trelsb_continue_text != '' ) {echo $trelsb_continue_text; } else { echo $defaults['trelsb_continue_text'];} ?>" class="regular-text" />
						<p id="continue-text-description" class="description"> Enter text here to change the continue button's text in the speed bump modal.</p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="trelsb_cancel_text">Cancel Button Text</label>
					</th>
					<td>
						<input name="trelsb_cancel_text" id="trelsb_cancel_text" aria-describedby="cancel-text-description"type="text" value="<?php if($trelsb_cancel_text != '' ) {echo $trelsb_cancel_text; } else { echo $defaults['trelsb_cancel_text'];} ?>" class="regular-text" />
						<p id="cancel-text-description" class="description"> Enter text here to change the cancel button's text in the speed bump modal.</p>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="submit">
			<input class="button button-primary" type="submit" name="trelsb_settings_submit" value="Save Changes" /> 
		</p>

	</form>
</div><!-- .wrap -->
						