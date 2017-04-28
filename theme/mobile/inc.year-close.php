<?php
    $earnings = round($this->getEarnings(), 2);
    $earningsWithFees = round($this->getEarningsWithFees(), 2);
    $sundays = $this->getSundaysWorked();
    $monthsWorked = $this->getMonthsWorked();
    $hoursWorked = round($this->getHoursWorked(), 2);
    $daysWorked = $this->getDaysWorked();
    $currentYear = date("Y");
?>
<div class="container" role="main">
    <div class="row">
        <?php
        if($this->alreadyBooked()) { ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title" style="color:#C51010"><?php echo translate("This year has been booked already"); ?>!</h3>
                </div>
                <div class="panel-body">
                    <p><?php echo translatevar("The current year (%s) has been booked and closed already", $currentYear); ?>!</p>
                    <p>
                        <a href="<?php echo placeHttpRoot(); ?>year" class="btn btn-primary"><?php echo translate("Check Yearly Overview"); ?></a>
                        <a href="<?php echo placeHttpRoot(); ?>" class="btn btn-primary"><?php echo translate("Back to dashboard"); ?></a>
                    </p>
                </div>
            </div>
        <?php }else{ ?>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Year closed and booked"); ?></h3>
                </div>
                <div class="panel-body">
                    <p><?php echo translatevar("The year %s has been successfully closed and booked", $currentYear); ?>.</p>

                    <h3><?php echo translate("Details of this year"); ?></h3>
                    <p>
                        <strong><?php echo translate("Earnings") . " (" . translate("without fees"); ?>):</strong> &euro; <?php echo $earnings; ?><br />
                        <strong><?php echo translate("Earnings") . " (" . translate("with fees"); ?>):</strong> &euro; <?php echo $earningsWithFees; ?><br />
                        <strong><?php echo translate("Sundays worked"); ?>:</strong> <?php $sundays; ?><br />
                        <strong><?php echo translate("Months worked"); ?>:</strong> <?php echo $monthsWorked; ?><br />
                        <strong><?php echo translate("Hours worked"); ?>:</strong> <?php echo $hoursWorked; ?><br />
                        <strong><?php echo translate("Days worked"); ?>:</strong> <?php echo $daysWorked; ?><br />
                    </p>

                    <p><a href="<?php echo placeHttpRoot(); ?>" class="btn btn-primary"><?php echo translate("Back to dashboard"); ?></a></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
