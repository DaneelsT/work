<main>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2><?php echo translate("Yearly Overview"); ?></h2>
            <table class="width50" style="font-size: 13px;">
                <tr>
                    <th><?php echo translate("Year"); ?></th>
                    <th><?php echo translate("Hours Worked"); ?></th>
                    <th><?php echo translate("Days Worked"); ?></th>
                    <th style="text-align:center"><?php echo translate("Earnings") . "<br />(<font color='#BC1717'>" . translate("without") . "</font> " . translate("fees"); ?>)</th>
                    <th style="text-align:center"><?php echo translate("Earnings") . "<br />(<font color='#28AF28'>" . translate("with") . "</font> " . translate("fees"); ?>)</th>
                    <th><?php echo translate("Sundays Worked"); ?></th>
                </tr>
                <?php
                // Fetch the months
                $months = $this->getYears();
                // Initialize the HTML string
                $html = "";
                // Iterate through all years and display them
                foreach$years as $year) {
                    $html .= '<tr>';
                    $html .= '<td>' . $year->getYear() . '</td>';
                    $html .= '<td>' . $year->getHoursWorked() . '</td>';
					$html .= '<td>' . $year->getDaysWorked() . '</td>';
                    $html .= '<td>&euro; ' . round($year->getEarnings(), 2) . '</td>';
                    $html .= '<td>&euro; ' . round($year->getEarningsWithFee(), 2) . '</td>';
                    $html .= '<td>'. $year->getSundays() . ' (&euro; ' . round($year->getSundayFee() * $year->getSundays(), 2) . ')</td>';
                    $html .= '</tr>';
                }
                // Print all generated HTML
                echo $html;
                ?>
            </table>
        </div>
    </div>
</main>
