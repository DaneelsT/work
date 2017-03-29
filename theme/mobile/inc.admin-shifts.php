<div class="container" role="main">
    <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("View shifts from a user"); ?></h3>
            </div>
            <div class="panel-body">
                <p><strong><?php echo translate("Select a user to view shifts from"); ?></strong></p>
                <table class="table">
                    <thead>
                        <tr>
                            <th><?php echo translate("Name"); ?></th>
                            <th><?php echo translate("Surname"); ?></th>
                            <th><?php echo translate("Actions"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $users = $this->getUsers();
                            $html = "";
                            foreach($users as $user) {
                            $html .= "<tr>";
                            $html .= "<td>" . $user->getName() . "</td>";
                            $html .= "<td>" . $user->getSurname() . "</td>";
                            $html .= "<td><a href=" . getBase() . "/admin/shift/" . $user->getId() . " class='btn btn-info'>" . translate("View Shifts") . "</a></td>";
                            }
                            echo $html;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
