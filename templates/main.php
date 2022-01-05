<div id="app">
<select id="group_folder">
		<option value="" selected="selected"><?php p($l->t("Home")); ?></option>
		<?php
		foreach($_['member_groups'] as $group){
			echo "<option value=\"".$group['gid']."\">".$group['gid']."</option>";
		}
		?>
</select>
</div>



