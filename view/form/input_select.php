<select name="<?php echo $name;?>" <?php if(isset($multiple) && $multiple === true):?>multiple <?php endif;?>id="<?php echo $id;?>" size="<?php if(isset($multiple) && $multiple === true) echo count($options);?>">
	<?php foreach($options as $value=>$option):
			if(isset($multiple) && $multiple === true):?>
			
				<option value="<?php echo $value;?>" <?php if(in_array($value, $selected)) echo ' selected="selected"';?>><?php echo $option;?></option>
			
			<?php else:?>
			
				<option value="<?php echo $value;?>" <?php if($value == $selected) echo ' selected="selected"';?>><?php echo $option;?></option>
	<?php endif;
		endforeach;?>
</select>