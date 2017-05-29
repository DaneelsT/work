<main>
    <?php if($this->yearNotFound()) { ?>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2 style="color:#C51010"><?php echo translate("The year you wanted to view is not booked"); ?>!</h2>

            <p>
                <?php echo translatevar("The year you are trying to view the details of (%s) was not yet booked by you.", $this->getYear()); ?>.
            </p>
            <p>
                <a href="<?php echo placeHttpRoot(); ?>year" class="button spacingRight"><?php echo translate("Check Yearly Overview"); ?></a>
                <a href="<?php echo placeHttpRoot(); ?>" class="button"><?php echo translate("Back to dashboard"); ?></a>
            </p>
        </div>
    </div>
    <?php }else { ?>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2><?php echo translate("Year Details"); ?> - <?php echo $this->getYear(); ?></h2>
            <table class="width50" style="font-size: 13px;">
                <tr>
                    <th><?php echo translate("Month"); ?></th>
                    <th><?php echo translate("Hours Worked"); ?></th>
                    <th><?php echo translate("Days Worked"); ?></th>
                    <th style="text-align:center"><?php echo translate("Earnings") . "<br />(<font color='#BC1717'>" . translate("without") . "</font> " . translate("fees"); ?>)</th>
                    <th style="text-align:center"><?php echo translate("Earnings") . "<br />(<font color='#28AF28'>" . translate("with") . "</font> " . translate("fees"); ?>)</th>
                    <th><?php echo translate("Sundays Worked"); ?></th>
                </tr>
                <?php
                // Fetch the months
                $months = $this->getMonths();
                // Initialize the HTML string
                $html = "";
                // Iterate through all months and display them
                foreach($months as $month) {
                    $html .= '<tr>';
                    $html .= '<td>' . translateMonth(getMonthNumber($month->getMonth())) . '</td>';
                    $html .= '<td>' . $month->getHoursWorked() . '</td>';
					$html .= '<td>' . $month->getDaysWorked() . '</td>';
                    $html .= '<td>&euro; ' . round($month->getEarnings(), 2) . '</td>';
                    $html .= '<td>&euro; ' . round($month->getEarningsWithFee(), 2) . '</td>';
                    $html .= '<td>'. $month->getSundays() . ' (&euro; ' . round($month->getSundayFee() * $month->getSundays(), 2) . ')</td>';
                    $html .= '</tr>';
                }
                // Print all generated HTML
                echo $html;
                ?>
            </table>
        </div>
    </div>
    <?php } ?>
</main>
