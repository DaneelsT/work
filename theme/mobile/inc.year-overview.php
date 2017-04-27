<div class="container" role="main">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Yearly Overview"); ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo translate("Year"); ?></th>
                                <th><?php echo translate("Hours Worked"); ?></th>
                                <th><?php echo translate("Days Worked"); ?></th>
                                <th><?php echo translate("Earnings") . "<br />(<font color='#BC1717'>" . translate("without") . "</font> " . translate("fees"); ?>)</th>
                                <th><?php echo translate("Earnings") . "<br />(<font color='#28AF28'>" . translate("with") . "</font> " . translate("fees"); ?>)</th>
                                <th><?php echo translate("Sundays Worked"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Fetch the years
                        $years = $this->getYears();
                        // Initialize the HTML string
                        $html = "";
                        // Iterate through all years and display them
                        foreach($years as $year) {
                            $html .= '<tr>';
                            $html .= '<td>' . $years->getMonth() . '</td>';
                            $html .= '<td>' . $years->getHoursWorked() . '</td>';
            				$html .= '<td>' . $years->getDaysWorked() . '</td>';
                            $html .= '<td>&euro; ' . round($years->getEarnings(), 2) . '</td>';
                            $html .= '<td>&euro; ' . round($years->getEarningsWithFee(), 2) . '</td>';
                            $html .= '<td>'. $years->getSundays() . ' (&euro; ' . round($years->getSundayFee() * $years->getSundays(), 2) . ')</td>';
                            $html .= '</tr>';
                        }
                        // Print all generated HTML
                        echo $html;
                        ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
