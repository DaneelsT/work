<div class="container" role="main">
    <div class="row">
        <?php if($this->yearNotFound()) { ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title" style="color:#C51010"><?php echo translate("The year you wanted to view is not booked"); ?>!</h3>
                </div>
                <div class="panel-body">
                    <p><?php echo translatevar("The year you are trying to view the details of (%s) was not yet booked by you.", $this->getYear()); ?>.</p>
                    <p>
                        <a href="<?php echo placeHttpRoot(); ?>year" class="button spacingRight"><?php echo translate("Check Yearly Overview"); ?></a>
                        <a href="<?php echo placeHttpRoot(); ?>" class="button"><?php echo translate("Back to dashboard"); ?></a>
                    </p>
                </div>
            </div>
        <?php }else{ ?>
        <div class="col-sm-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Year Details"); ?> - <?php echo $this->getYear(); ?></h3>
                </div>
                <div class="panel-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th><?php echo translate("Month"); ?></th>
                                <th><?php echo translate("Hours Worked"); ?></th>
                                <th><?php echo translate("Days Worked"); ?></th>
                                <th><?php echo translate("Earnings") . "<br />(<font color='#BC1717'>" . translate("without") . "</font> " . translate("fees"); ?>)</th>
                                <th><?php echo translate("Earnings") . "<br />(<font color='#28AF28'>" . translate("with") . "</font> " . translate("fees"); ?>)</th>
                                <th><?php echo translate("Sundays Worked"); ?></th>
                                <th style="text-align:center"><?php echo translate("Details"); ?></th>
                            </tr>
                        </thead>
                        <tbody>
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
                            $html .= '<a class="button right buttonSpacingRight" href="' . getHttpRoot() . 'year/details/' . $shift->getYear() .'">' . translate("Detailed View") . '</a>';
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
        <?php } ?>
    </div>
</div>
