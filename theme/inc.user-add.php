<main>
    <?php if( $this->userAdded() ) { ?>
        <div class="panel panelSeparatorTop">
            <div class="panelContent">
                <h2><?php echo translate("User added"); ?></h2>

                <p><?php echo translate("User successfully added to the Work Webinterface"); ?>.</p>
                <p><a href="/users" class="button"><?php echo translate("Return to user overview"); ?></a></p>
            </div>
        </div>
    <?php } elseif( $this->invalidInput() ) { ?>
        <div class="panel panelSeparatorTop">
            <div class="panelContent">
                <h2><?php echo translate("Invalid input"); ?></h2>
                <p><?php echo translate("Some fields were not filled in (correctly). Fill in all fields correctly and try again"); ?>.</p>
            </div>
        </div>
    <?php } elseif( $this->existingUser() ) { ?>
        <div class="panel panelSeparatorTop">
            <div class="panelContent">
                <h2><?php echo translate("User already exists"); ?></h2>
                <p><?php echo translate("A user with this username and/or email already exists"); ?>!</p>
            </div>
        </div>
    <?php } ?>
    <div class="panel panelSeparatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Add a new user"); ?></h2>
            <h4><?php echo translate("Basic information"); ?></h4>

            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            	<input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">
                <div>
                    <label class="stdLabelWidth"><?php echo translate("Name"); ?></label>
                    <input type="text" name="user_name" class="spacingRight">
                    <label class="stdLabelWidth"><?php echo translate("Surname"); ?></label>
                    <input type="text" name="user_surname">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Username"); ?></label>
                    <input type="text" name="user_username">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("E-mail"); ?></label>
                    <input type="text" name="user_email">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Gender"); ?></label>
                    <input type="radio" name="user_gender" value="1" checked><?php echo translate("Male"); ?>
                    <input type="radio" name="user_gender" class="spacingLeft" value="2"><?php echo translate("Female"); ?>
                </div>

                <h4><?php echo translate("User password"); ?></h4>
                <div>
                    <label class="stdLabelWidth"><?php echo translate("Password"); ?></label>
                    <input type="password" id="txtUserPassword" name="user_password" placeholder="<?php echo translate("Enter a password"); ?>">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Password (repeat)"); ?></label>
                    <input type="password" id="txtUserPasswordRepeat" name="user_password_repeat" placeholder="<?php echo translate("Repeat password"); ?>">
                </div>

                <input type="submit" name="add_user" class="button spacingTop" value="<?php echo translate("Add User"); ?>">
            </form>
        </div>
    </div>
</main>
