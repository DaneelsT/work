<div class="container" role="main">
    <div class="row">
<?php
    $user = $this->getUser();
    $shifts = $this->getShifts();

    if($this->userNotFound()) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("User not found"); ?>!</h3>
            </div>
            <div class="panel-body">
                <p><?php echo translate("A user with that id could not be found"); ?>!</p>
                <a href="<?php placeHttpRoot(); ?>admin/shifts" class="btn btn-primary"><?php echo translate("Back to shifts page"); ?></a>
            </div>
        </div>
    <?php }elseif($this->noShifts()) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("No shifts found"); ?></h3>
            </div>
            <div class="panel-body">
                <p>
                    <?php echo translate("No shifts for this user were found in the database!<br />
                    Verify that this user has worked a shift before or shifts aren't booked."); ?>
                </p>
                <a href="<?php placeHttpRoot(); ?>admin/shifts" class="btn btn-primary"><?php echo translate("Back to shifts page"); ?></a>
            </div>
        </div>
    <?php }else{ ?>
        <?php
            $totalPay = round($this->getTotalPay(), 2);
            $totalPayWithFees = round($this->getTotalPayWithFees(), 2);
            $payPerHour = $this->getPayPerHour();
            $sundayFee = $this->getSundayFee();
            $hoursWorked = round($this->getTotalHours(), 1);
            $currentMonth = date("F");
        ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Current Earnings"); ?> - <?php echo $currentMonth; ?> (&euro; <?php echo $totalPayWithFees; ?>)</h3>
            </div>
        <div class="panel-body">
            <p>
                <strong><?php echo translate("Current earnings") . " (" . translate("without fees"); ?>):</strong> &euro; <?php echo $totalPay; ?><br />
                <strong><?php echo translate("Current earnings") . " (" . translate("with fees"); ?>):</strong> &euro; <?php echo $totalPayWithFees; ?><br />
                <strong><?php echo translate("Current month"); ?>:</strong> <?php echo $currentMonth; ?><br /><br />

                <strong><?php echo translate("Earnings per hour"); ?>:</strong> &euro; <?php echo $payPerHour; ?><br />
                <strong><?php echo translate("Sunday fee"); ?>:</strong> &euro; <?php echo $sundayFee; ?><br />
                <strong><?php echo translate("Hours worked"); ?>:</strong> <?php echo $hoursWorked; ?><br />
            </p>
        </div>
    </div>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo translate("View shifts from") . " " . $user->getFullName(); ?></h3>
        </div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
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
                            $html .= '<td>' . $shift->getId() . '</td>';
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
        </div>
    </div>
    <?php } ?>
    </div>
</div>
