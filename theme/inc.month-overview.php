<main>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2><?php echo translate("Monthly Overview"); ?> - <a href="<?php echo placeHttpRoot(); ?>year/close" id="closebutton" class="button right"><?php echo translate("Close Year"); ?></a></h2>
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
                    $html .= '<td>' . getMonthName($month->getMonth()) . '</td>';
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
</main>
