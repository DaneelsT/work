<?php
$totalPay = round($this->getTotalPay(), 2);
$totalPayWithFees = round($this->getTotalPayWithFees(), 2);
$payPerHour = $this->getPayPerHour();
$sundayFee = $this->getSundayFee();
$hoursWorked = round($this->getTotalHours(), 1);
$currentMonth = date("F");
?>
<div class="container" role="main">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Current Earnings"); ?> - <?php echo $currentMonth; ?> (&euro; <?php echo $totalPayWithFees; ?>)</h3>
                </div>
                <div class="panel-body">
                    <p>
                    <strong><?php echo translate("Current earnings") . " (" . translate("without fees"); ?>):</strong> &euro; <?php echo $totalPay; ?><br />
                    <strong style="color:green"><?php echo translate("Current earnings") . " (" . translate("with fees"); ?>):</strong> &euro; <?php echo $totalPayWithFees; ?><br />
                    <strong><?php echo translate("Current month"); ?>:</strong> <?php echo $currentMonth; ?><br /><br />

                    <strong><?php echo translate("Earnings per hour"); ?>:</strong> &euro; <?php echo $payPerHour; ?><br />
                    <strong><?php echo translate("Sunday fee"); ?>:</strong> &euro; <?php echo $sundayFee; ?><br />
                    <strong><?php echo translate("Hours worked"); ?>:</strong> <?php echo $hoursWorked; ?><br />
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Add a new shift"); ?></h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <div class="input-group spacingBottom">
                            <span class="input-group-addon" id="addon-date"><?php echo translate('Date'); ?></span>
                            <input type="date" class="form-control" name="date" id="date" value="<?php echo date('Y-m-d'); ?>" aria-describedby="basic-addon1">
                        </div>

                        <div class="input-group spacingBottom">
                            <span class="input-group-addon" id="addon-starttime"><?php echo translate('Start Time'); ?></span>
                            <input type="text" class="form-control" maxlength="5" name="startTime" id="startTime" placeholder="10:00" />
                        </div>

                        <div class="input-group spacingBottom">
                            <span class="input-group-addon" id="addon-endtime"><?php echo translate('End Time'); ?></span>
                            <input type="text" class="form-control" maxlength="5" name="endTime" id="endTime" placeholder="19:00" />
                        </div>

                        <div class="checkbox">
                            <label><input type="checkbox" name="holiday" id="holiday"><?php echo translate("This is a holiday"); ?></label>
                        </div>
                        <input type="submit" name="add_shift" class="btn btn-success" value="<?php echo translate("Add Shift"); ?>">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <?php echo translate("Current Shifts"); ?> - <?php echo $currentMonth; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo translate("Date"); ?></th>
                                <th><?php echo translate("Start Time"); ?></th>
                                <th><?php echo translate("End Time"); ?></th>
                                <th><?php echo translate("Total Hours"); ?></th>
                                <th></th>
                                <th><?php echo translate("Actions"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
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
                                $html .= '<a class="btn btn-danger buttonSpacingRight" href="' . getHttpRoot() . 'shift/remove/' . $shift->getId() .'/">' . translate("Remove") . '</a>';
                                $html .= '<a class="btn btn-warning" href="' . getHttpRoot() . 'shift/edit/' . $shift->getId() .'/">' . translate("Edit") . '</a>';
                                $html .= '</td>';
                                $html .= '</tr>';
                            }
                            // Print all generated HTML
                            echo $html;
                        ?>
                    </tbody>
                    </table>
                    <a href="<?php echo placeHttpRoot(); ?>month/close" id="closemonth" class="btn btn-primary"><?php echo translate("Close Month"); ?></a>
                </div>
                </div>
          </div>
      </div>
</div>
