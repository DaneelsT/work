<main>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
        	<?php
            	$totalPay = round($this->getTotalPay(), 2);
                $totalPayWithFees = round($this->getTotalPayWithFees(), 2);
    			$payPerHour = $this->getPayPerHour();
    			$sundayFee = $this->getSundayFee();
    			$hoursWorked = round($this->getTotalHours(), 1);
                $currentMonth = translateMonth();
			?>
            <h2><?php echo translate("Current Earnings"); ?> - <?php echo $currentMonth; ?> (&euro; <?php echo $totalPayWithFees; ?>)</h2>
            <p style="font-size:15px;">
                <strong style="font-size: 15px;"><?php echo translate("Current earnings") . " (" . translate("without fees"); ?>):</strong> &euro; <?php echo $totalPay; ?><br />
                <strong style="color:green;font-size:15px;"><?php echo translate("Current earnings") . " (" . translate("with fees"); ?>):</strong> &euro; <?php echo $totalPayWithFees; ?><br />
                <strong><?php echo translate("Current month"); ?>:</strong> <?php echo $currentMonth; ?><br /><br />

                <strong><?php echo translate("Earnings per hour"); ?>:</strong> &euro; <?php echo $payPerHour; ?><br />
                <strong><?php echo translate("Sunday fee"); ?>:</strong> &euro; <?php echo $sundayFee; ?><br />
                <strong><?php echo translate("Hours worked"); ?>:</strong> <?php echo $hoursWorked; ?><br />
            </p>
        </div>
    </div>
    <div class="panel panelSeparatorBottom" style="width:29%;float:left;">
        <div class="panelContent">
            <h2><?php echo translate("Add a new shift"); ?></h2>

            <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <div>
                    <label class="stdLabelWidth"><?php echo translate("Date"); ?></label>
                    <input type="date" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" class="spacingRight" />
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Start Time"); ?></label>
                    <input type="text" maxlength="5" name="startTime" id="startTime" size="1" placeholder="10:00" />
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("End Time"); ?></label>
                    <input type="text" maxlength="5" name="endTime" id="endTime" size="1" placeholder="19:00" />
                </div>
                <div class="formContainerSeperator">
                	<label class="stdLabelWidth"><?php echo translate("Is holiday"); ?></label>
                    <label><input type="checkbox" name="holiday" id="holiday" /><?php echo translate("This is a holiday"); ?></label>
                </div>
                <input type="submit" name="add_shift" class="button spacingTop spacingRight" value="<?php echo translate("Add Shift"); ?>">
            </form>
        </div>
    </div>
    <div class="panel panelSeparatorBottom" style="width:70%;float:right;">
        <div class="panelContent">
            <h2><?php echo translate("Current Shifts"); ?> - <?php echo $currentMonth; ?> <a href="<?php echo placeHttpRoot(); ?>month/close" id="closebutton" class="button right"><?php echo translate("Close Month"); ?></a></h2>
            <table class="width50" id="shifts">
        		<tr>
	            	<th><?php echo translate("Date"); ?></th>
	            	<th><?php echo translate("Start Time"); ?></th>
	            	<th><?php echo translate("End Time"); ?></th>
	            	<th><?php echo translate("Total Hours"); ?></th>
	            	<th></th>
	            	<th><?php echo translate("Actions"); ?></th>
            	</tr>
            </table>
        </div>
    </div>
</main>
