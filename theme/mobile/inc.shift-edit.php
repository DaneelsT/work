<div class="container" role="main">
	<div class="row">
		<?php
	    	$shift = $this->getShift();
	        $startTime = date('H:i', $shift->getStartTime() - (60*60));
	        $endTime = date('H:i', $shift->getEndTime() - (60*60));
	    ?>
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo translate("Edit shift from") . " " . $shift->getDate(); ?></h3>
			</div>
			<div class="panel-body">
				<form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<div class="input-group spacingBottom">
						<span class="input-group-addon" id="addon-date"><?php echo translate('Date'); ?></span>
						<input type="date" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" aria-describedby="basic-addon1">
					</div>

					<div class="input-group spacingBottom">
						<span class="input-group-addon" id="addon-starttime"><?php echo translate('Start Time'); ?></span>
						<input type="text" class="form-control" maxlength="5" name="startTime" id="startTime" value="<?php echo $startTime; ?>" />
					</div>

					<div class="input-group spacingBottom">
						<span class="input-group-addon" id="addon-endtime"><?php echo translate('End Time'); ?></span>
						<input type="text" class="form-control" maxlength="5" name="endTime" id="endTime" value="<?php echo $endTime; ?>" />
					</div>

					<div class="checkbox">
						<label><input type="checkbox" name="holiday" id="holiday"><?php echo translate("This is a holiday"); ?></label>
					</div>

	                <input type="submit" name="edit_shift" class="btn btn-success" value="<?php echo translate("Save Changes"); ?>">
	            </form>
			</div>
		</div>
	</div>
</div>
