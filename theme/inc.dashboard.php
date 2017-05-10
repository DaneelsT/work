<main>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">

            <!-- testing -->
            <button class="button" id="testButton">Get shifts</button>

        	<?php
            	$totalPay = round($this->getTotalPay(), 2);
                $totalPayWithFees = round($this->getTotalPayWithFees(), 2);
    			$payPerHour = $this->getPayPerHour();
    			$sundayFee = $this->getSundayFee();
    			$hoursWorked = round($this->getTotalHours(), 1);
                $currentMonth = date("F");
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
            <table class="width50">
        		<tr>
	            	<th><?php echo translate("Date"); ?></th>
	            	<th><?php echo translate("Start Time"); ?></th>
	            	<th><?php echo translate("End Time"); ?></th>
	            	<th><?php echo translate("Total Hours"); ?></th>
	            	<th></th>
	            	<th><?php echo translate("Actions"); ?></th>
            	</tr>
            	<?php
            	// Fetch the shifts
            	$shifts = $this->getShifts();
				// Initialize the HTML string
				$html = "";
				// Iterate through all shifts and display them
				foreach($shifts as $shift) {
					$dateFormatted = date('d-m-Y', strtotime($shift->getDate()));
					// wtf PHP, really.
					$startTime = date('H:i', $shift->getStartTime() - (60*60));
					$endTime = date('H:i', $shift->getEndTime() - (60*60));
					$timeDifference = ($shift->getEndTime() - $shift->getStartTime());
					$hoursWorked = (int)$timeDifference / 60 / 60;
					$minutesWorked = (int)($timeDifference - ($hoursWorked * 60 * 60)) / 60;
					$dayHours = ($hoursWorked + ($minutesWorked / 60));

                	$html .= '<tr>';
                    $html .= '<td>' . $dateFormatted . '</td>';
                    $html .= '<td>' . $startTime . '</td>';
                    $html .= '<td>' . $endTime . '</td>';
                    $html .= '<td>' . round($dayHours, 1) . '</td>';
					if($shift->isSunday()) {
						if(dayIsSunday($shift->getDate())) {
							$type = translate("SUNDAY");
						}else{
							$type = translate("HOLIDAY");
						}
                        $html .= '<td style="color:#28AF28">' . $type . ' (+ &euro; ' . $this->getSundayFee() .')</td>';
					}else{
                        $html .= '<td></td>';
                    }
                    $html .= '<td>';
                    $html .= '<a class="button right buttonRed" href="' . getHttpRoot() . 'shift/remove/' . $shift->getId() .'/">' . translate("Remove") . '</a>';
                    $html .= '<a class="button right buttonSpacingRight" href="' . getHttpRoot() . 'shift/edit/' . $shift->getId() .'/">' . translate("Edit") . '</a>';
                    $html .= '</td>';
                    $html .= '</tr>';
				}
				// Print all generated HTML
				echo $html;
				?>
            </table>
        </div>
    </div>
</main>
