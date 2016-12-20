<main>
    <?php
    use \Carbon\Application\Application;
    $user = $this->getUser();

    if( $this->userUpdated() ) { ?>
    <div class="panel panelSeparatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Account updated"); ?></h2>

            <p>
                <?php echo translate("Your account has been updated successfully"); ?>!
            </p>

            <p>
                <a href="<?php placeHttpRoot(); ?>" class="button"><?php echo translate("Go to dashboard"); ?></a>
            </p>
        </div>
    </div>
    <?php } if( $this->invalidInput() ) { ?>
    <div class="panel panelSeparatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Invalid Input"); ?></h2>

            <p>
                <?php echo translate("Some fields were left empty or were not filled in correctly"); ?>
            </p>
            <p>
                <a href="profile" class="button"><?php echo translate("Go back and try again"); ?></a>
            </p>
        </div>
    </div>
    <?php }else{
        $maleChecked = "";
        $femaleChecked = "";
        if( $user->isMale() ) {
            $maleChecked = 'checked="checked"';
        } elseif( $user->isFemale() ) {
            $femaleChecked = 'checked="checked"';
        }

        // noob way of doing it, but it works, yay.
        $enSelected = "";
        $nlSelected = "";
        if($user->getLanguage() == "en_US") {
            $enSelected = 'selected="selected"';
        }elseif($user->getLanguage() == "nl_BE") {
            $nlSelected = 'selected="selected"';
        }
    ?>
    <div class="panel panelSeparatorTop" style="float:left;width:30%">
        <div class="panelContent">
            <h2><?php echo $user->getFullName(); ?></h2>
                <div>
                    <label class="stdLabelWidth"><?php echo translate("Name"); ?></label>
                    <?php echo $user->getName(); ?>
                    <label class="spacingRight"></label>

                    <label class="stdLabelWidth"><?php echo translate("Surname"); ?></label>
                    <?php echo $user->getSurName(); ?>
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Username"); ?></label>
                    <?php echo $user->getUserName(); ?>
                </div>
            	<div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("E-mail"); ?></label>
                    <?php echo $user->getEmail(); ?>
            	</div>
            	<div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Gender"); ?></label>
                    <?php echo $user->getGenderString(); ?>
               	</div>
               	<div class="formContainerSeparator">
               		<label class="stdLabelWidth"><?php echo translate("Hourly Pay"); ?></label>
               		<?php echo $user->getHourlyPay(); ?>
               	</div>
               	<div class="formContainerSeparator">
               		<label class="stdLabelWidth"><?php echo translate("Sunday Fee"); ?></label>
               		<?php echo $user->getSundayFee(); ?>
               	</div>
               	<div class="formContainerSeparator">
               	    <label class="stdLabelWidth"><?php echo translate("Language"); ?></label>
               	    <?php
               	    if($user->getLanguage() == 'nl_BE') {
               	        $lang = 'Nederlands';
               	    }else{
               	        $lang = 'English';
               	    }
               	    echo $lang;
                    ?>
               	</div>
               	<?php
               	if($user->isAdmin()) {
               		echo '<p><strong style="color:red;">' . translate("ADMINISTRATOR") . '</strong></p>';
               	}
				?>
        </div>
    </div>
    <div class="panel panelSeperatorTop" style="float:right;width:69%">
    	<div class="panelContent">
    		<h2><?php echo translate("Edit my profile"); ?></h2>
    		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            	<h3><?php echo translate("General Settings"); ?></h3>
                <div>
                    <label class="stdLabelWidth"><?php echo translate("Name"); ?></label>
                    <input type="text" name="user_name" value="<?php echo $user->getName(); ?>" class="spacingRight">
                    <label class="stdLabelWidth"><?php echo translate("Surname"); ?></label>
                    <input type="text" name="user_surname" value="<?php echo $user->getSurName(); ?>">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Username"); ?></label>
                    <input type="text" name="user_username" value="<?php echo $user->getUsername(); ?>" disabled>
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("E-mail"); ?></label>
                    <input type="text" name="user_email" value="<?php echo $user->getEmail(); ?>">
                </div>
                <div class="formContainerSeparator formSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Gender"); ?></label>
                    <label for="male">
                        <input type="radio" name="user_gender" id="male" value="1" <?php echo $maleChecked; ?> />
                        <?php echo translate("Male"); ?>
                    </label>
                    <label for="female">
                        <input type="radio" name="user_gender" id="female" class="spacingLeft" value="2" <?php echo $femaleChecked; ?> />
                        <?php echo translate("Female"); ?>
                    </label>
                </div>
            	<h3><?php echo translate("Payment Settings"); ?></h3>
                <div class="formContainerSeparator formSeparator">
                	<label class="stdLabelWidth"><?php echo translate("Hourly Pay"); ?></label>
                	<input type="text" name="hourly_pay" class="spacingRight" size="1" placeholder="<?php echo Application::getInstance()->getConfiguration("hourly_pay"); ?>" value="<?php echo $user->getHourlyPay(); ?>">
                	<label class="stdLabelWidth"><?php echo translate("Sunday Fee"); ?></label>
                	<input type="text" name="sunday_fee" size="1" placeholder="<?php echo Application::getInstance()->getConfiguration("sunday_fee"); ?>" value="<?php echo $user->getSundayFee(); ?>">
                </div>
                <h3><?php echo translate("Language Settings"); ?></h3>
                <div class="formContainerSeparator formSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Language"); ?></label>
                    <select name="language">
                        <option name="nl_BE" <?php echo $nlSelected; ?>>Nederlands</option>
                        <option name="en_US" <?php echo $enSelected; ?>>English</option>
                    </select>
                </div>
                <h3><?php echo translate("Password Settings"); ?></h3>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Password"); ?></label>
                    <input type="password" name="user_password_new" placeholder="<?php echo translate("Enter new password"); ?>">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Password (repeat)"); ?></label>
                    <input type="password" name="user_password_repeat" placeholder="<?php echo translate("Repeat new password"); ?>">
                </div>
                <input type="submit" name="edit_user" class="button spacingTop spacingRight" value="<?php echo translate("Save Changes"); ?>">
            </form>
    	</div>
    </div>
    <?php } ?>
</main>
