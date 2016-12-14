<?php
$totalPay = round($this->getTotalPay(), 2);
$sundays = $this->getSundaysWorked();
$totalPayWithFees = round($this->getTotalPayWithFees(), 2);
$hoursWorked = round($this->getTotalHours(), 1);
$daysWorked = $this->getDaysWorked();
$currentMonth = date("F");
?>
<main>
    <?php
    if($this->alreadyBooked()) { ?>
    <div class="panel panelSeparatorBottom">
        <div class="panelContent">
            <h2 style="color:#C51010"><?php echo translate("This month has been booked already"); ?>!</h2>
            <p><?php echo translatevar("The current month (%s) has been booked and closed already", $currentMonth); ?>!</p>
            <p>
                <a href="<?php echo placeHttpRoot(); ?>month" class="button spacingRight"><?php echo translate("Check Monthly Overview"); ?></a>
                <a href="<?php echo placeHttpRoot(); ?>" class="button"><?php echo translate("Back to dashboard"); ?></a>
            </p>
        </div>
    </div>
    <?php }else{ ?>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2><?php echo translate("Month closed and booked"); ?></h2>
            
            <p><?php echo translatevar("The month %s has been successfully closed and booked", $currentMonth); ?>.</p>
            
            <h3><?php echo translate("Details of this month"); ?></h3>
            <p>
                <strong><?php echo translate("Earnings") . " (" . translate("without fees"); ?>):</strong> &euro; <?php echo $totalPay; ?><br />
                <strong><?php echo translate("Earnings") . " (" . translate("with fees"); ?>):</strong> &euro; <?php echo $totalPayWithFees; ?><br />
                <strong><?php echo translate("Sundays worked"); ?>:</strong> <?php $sundays; ?><br />
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