<main>
    <?php
    use \Carbon\Application\Application;
    $app = Application::getInstance();

    if($this->invalidToken()) {
    ?>
    <div class="panel panelSeperatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Invalid token"); ?>!</h2>

            <p><?php echo translatevar("The token you have supplied (%s) does not appear to be valid", $app->getRouter()->getSegment(2)); ?></p>
        </div>
    </div>
    <?php }elseif($this->userExists()) { ?>
    <div class="panel panelSeperatorTop">
        <div class="panelContent">
            <h2><?php echo translate("User already exists"); ?></h2>

            <p><?php echo translate("A user with the supplied username or email already exists in the system"); ?>!</p>
        </div>
    </div>
    <?php }elseif($this->userAdded()) { ?>
    <div class="panel panelSeperatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Account created"); ?>!</h2>

            <p><?php echo translate("Your account has been created successfully"); ?>!</p>
            <a href="<?php placeHttpRoot(); ?>login/" class="button"><?php echo translate("Proceed to the login page"); ?></a>
        </div>
    </div>
    <?php }elseif($this->invalidInput()) { ?>
    <div class="panel panelSeparatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Invalid Input"); ?></h2>

            <p>
                <?php echo translate("Some fields were left empty or were not filled in correctly"); ?>
            </p>
            <p>
                <a href="<?php echo $app->getRouter()->getSegment(2); ?>" class="button"><?php echo translate("Go back and try again"); ?></a>
            </p>
        </div>
    </div>
    <?php }elseif($this->isVerified()) { ?>
    <div class="panel info-important">
        <h4><?php echo translate("Your password will be encrypted upon registration. Nobody will be able to view or decrypt your password"); ?>.</h4>
    </div>
    <div class="panel panelSeparatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Finish your registration"); ?></h2>
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
                    <input type="text" name="user_email" value="<?php echo $this->getEmail(); ?>" disabled>
                </div>
                <div class="formContainerSeparator formSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Gender"); ?></label>
                    <label for="male">
                        <input type="radio" name="user_gender" id="male" value="1" checked />
                        <?php echo translate("Male"); ?>
                    </label>
                    <label for="female">
                        <input type="radio" name="user_gender" id="female" class="spacingLeft" value="2" />
                        <?php echo translate("Female"); ?>
                    </label>
                </div>

                <h4><?php echo translate("Payment Settings"); ?></h4>
                <div class="formContainerSeperator formSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Hourly Pay"); ?></label>
                    <input type="text" name="hourly_pay" class="spacingRight" placeholder="<?php echo $app->getConfiguration("hourly_pay"); ?>" size="1">
                    <label class="stdLabelWidth"><?php echo translate("Sunday Fee"); ?></label>
                    <input type="text" name="sunday_fee" placeholder="<?php echo $app->getConfiguration("sunday_fee"); ?>" size="1">
                </div>

                <h4><?php echo translate("Language Settings"); ?></h4>
                <div class="formContainerSeperator formSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Language"); ?></label>
                    <select name="language">
                        <option value="nl_BE" selected="selected">Nederlands</option>
                        <option value="en_US">English</option>
                    </select>
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

                <input type="submit" name="add_user" class="button spacingTop" value="<?php echo translate("Register"); ?>">
            </form>
        </div>
    </div>
    <?php } ?>
</main>
