
<div>
<h2>Communities Author Roles</h2>

<form action="<?php echo $form_action; ?>" method="post">
	<?php settings_fields($settings_field); ?>
	<?php do_settings_sections($settings_section); ?>
	 
	<input class="button-primary" name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form>
</div>