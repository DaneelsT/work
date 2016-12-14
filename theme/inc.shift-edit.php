<main>
	<?php
    	$shift = $this->getShift();
        $startTime = date('H:i', $shift->getStartTime() - (60*60));
        $endTime = date('H:i', $shift->getEndTime() - (60*60));
    ?>
	<div class="panel panelSeperatorTop">
		<div class="panelContent">
			<h2><?php echo translate("Edit shift from") . " " . $shift->getDate(); ?></h2>
			<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<div>
					<label class="stdLabelWidth"><?php echo translate("Date"); ?></label>
					<input type="date" name="date" id="date" value="<?php echo $shift->getDate(); ?>" class="spacingRight">
				</div>
				<div class="formContainerSeparator">
					<label class="stdLabelWidth"><?php echo translate("Start Time"); ?></label>
                    <input type="text" maxlength="5" name="startTime" id="startTime" size="1" value="<?php echo $startTime; ?>" />
				</div>
				<div class="formContainerSeparator">
					<label class="stdLabelWidth"><?php echo translate("End Time"); ?></label>
                    <input type="text" maxlength="5" name="endTime" id="endTime" size="1" value="<?php echo $endTime; ?>" />
				</div>
				<div class="formContainerSeperator">
					<label class="stdLabelWidth"><?php echo translate("Is Holiday"); ?></label>
					<?php
					if($shift->isSunday()) {
						$checked = "checked='checked'";
					}else{
						$checked = "";
					}
					?>
					<label><input type="checkbox" name="holiday" id="holiday" <?php echo $checked; ?> /><?php echo translate("This is a holiday"); ?></label>
				</div>
                <input type="submit" name="edit_shift" class="button spacingTop spacingRight" value="<?php echo translate("Save Changes"); ?>">
            </form>
		</div>
	</div>
</main>