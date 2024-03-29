<?php
    $earnings = round($this->getEarnings(), 2);
    $earningsWithFees = round($this->getEarningsWithFees(), 2);
    $sundays = $this->getSundaysWorked();
    $monthsWorked = $this->getMonthsWorked();
    $hoursWorked = round($this->getHoursWorked(), 2);
    $daysWorked = $this->getDaysWorked();
    $currentYear = date("Y");
?>
<main>
    <?php
    if($this->alreadyBooked()) { ?>
    <div class="panel panelSeparatorBottom">
        <div class="panelContent">
            <h2 style="color:#C51010"><?php echo translate("This year has been booked already"); ?>!</h2>
            <p><?php echo translatevar("The current year (%s) has been booked and closed already", $currentYear); ?>!</p>
            <p>
                <a href="<?php echo placeHttpRoot(); ?>year" class="button spacingRight"><?php echo translate("Check Yearly Overview"); ?></a>
                <a href="<?php echo placeHttpRoot(); ?>" class="button"><?php echo translate("Back to dashboard"); ?></a>
            </p>
        </div>
    </div>
    <?php }else{ ?>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2><?php echo translate("Year closed and booked"); ?></h2>

            <p><?php echo translatevar("The year %s has been successfully closed and booked", $currentYear); ?>.</p>

            <h3><?php echo translate("Details of this year"); ?></h3>
            <p>
                <strong><?php echo translate("Earnings") . " (" . translate("without fees"); ?>):</strong> &euro; <?php echo $earnings; ?><br />
                <strong><?php echo translate("Earnings") . " (" . translate("with fees"); ?>):</strong> &euro; <?php echo $earningsWithFees; ?><br />
                <strong><?php echo translate("Sundays worked"); ?>:</strong> <?php echo $sundays; ?><br />
                <strong><?php echo translate("Months worked"); ?>:</strong> <?php echo $monthsWorked; ?><br />
                <strong><?php echo translate("Hours worked"); ?>:</strong> <?php echo $hoursWorked; ?><br />
                <strong><?php echo translate("Days worked"); ?>:</strong> <?php echo $daysWorked; ?><br />
            </p>

            <p>
                <a href="<?php echo placeHttpRoot(); ?>" class="button"><?php echo translate("Back to dashboard"); ?></a>
            </p>
        </div>
    </div>
    <?php } ?>
</main>
