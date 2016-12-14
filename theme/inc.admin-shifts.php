<main>
    <div class="panel panelSeperatorBottom">
        <div class="panelContent">
            <h2><?php echo translate("View shifts from a user"); ?></h2>
            
            <p><?php echo translate("Select a user to view shifts from"); ?></p>
            
            <?php
            $users = $this->getUsers();
            $html = "";
            ?>
            <table class="width50">
                <tr>
                    <th><?php echo translate("Name"); ?></th>
                    <th><?php echo translate("Surname"); ?></th>
                    <th><?php echo translate("Actions"); ?></th>
                </tr>
            <?php
            foreach($users as $user) {
                $html .= "<tr>";
                $html .= "<td>" . $user->getName() . "</td>";
                $html .= "<td>" . $user->getSurname() . "</td>";
                $html .= "<td><a href=" . getBase() . "/admin/shift/" . $user->getId() . " class='button'>" . translate("View Shifts") . "</a></td>";
            }
            echo $html;
            ?>
        </div>
    </div>
</main>