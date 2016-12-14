<main>
    <?php
    use \Carbon\Application\Application;
    if($this->userNotFound()) { ?>
        <div class="panel panelSeperatorTop">
            <div class="panelContent">
                <h2><?php echo translate("User not found"); ?>!</h2>

                <p><?php echo translate("A user with that ID could not be found"); ?>!</p>
                <a href="<?php placeHttpRoot(); ?>admin/users" class="button"><?php echo translate("Back to users overview"); ?></a>
            </div>
        </div>
    <?php }elseif($this->userUpdated()) { ?>
        <div class="panel panelSeperatorTop">
            <div class="panelContent">
                <h2><?php echo translate("User successfully updated"); ?>!</h2>

                <p><?php echo translate("This user's profile has been successfully updated"); ?>!</p>
                <a href="<?php placeHttpRoot(); ?>admin/users" class="button"><?php echo translate("Back to users overview"); ?></a>
            </div>
        </div>
    <?php }else{
        $user = $this->getUser();
    ?>
    <h2>USER EDITTING NOT IMPLEMENTED YET</h2>
    <div class="panel panelSeperatorTop">
    	<div class="panelContent">
    		<h2><?php echo translate("Edit"); ?> <?php echo $user->getFullName(); ?></h2>
    		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            	<h3><?php echo translate("General Settings"); ?></h3>
                <div>
                    <label class="stdLabelWidth"><?php echo translate("Name"); ?></label>
                    <input type="text" name="name" value="<?php echo $user->getName(); ?>" class="spacingRight">
                    <label class="stdLabelWidth"><?php echo translate("Surname"); ?></label>
                    <input type="text" name="surname" value="<?php echo $user->getSurName(); ?>">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Username"); ?></label>
                    <input type="text" name="username" value="<?php echo $user->getUsername(); ?>" disabled>
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("E-mail"); ?></label>
                    <input type="text" name="email" value="<?php echo $user->getEmail(); ?>">
                </div>
                <div class="formContainerSeparator formSeparator">
                    <label class="stdLabelWidth">Gender</label>
                    <input type="radio" name="gender" value="1">Male
                    <input type="radio" name="gender" class="spacingLeft" value="2">Female
                </div>
            	<h3><?php echo translate("Payment Settings"); ?></h3>
                <div class="formContainerSeparator formSeparator">
                	<label class="stdLabelWidth"><?php echo translate("Hourly Pay"); ?></label>
                	<input type="text" name="hourly_pay" class="spacingRight" placeholder="<?php echo Application::getInstance()->getConfiguration("hourly_pay"); ?>" size="1" value="<?php echo $user->getHourlyPay(); ?>">
                	<label class="stdLabelWidth"><?php echo translate("Sunday Fee"); ?></label>
                	<input type="text" name="sunday_fee" placeholder="<?php echo Application::getInstance()->getConfiguration("sunday_fee"); ?>" size="1" value="<?php echo $user->getSundayFee(); ?>">
                </div>
                <h3><?php echo translate("Language Settings"); ?></h3>
                <div class="formContainerSeparator formSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Language"); ?></label>
                    <select name="language">
                        <option name="nl_BE">Nederlands</option>
                        <option name="en_US">English</option>
                    </select>
                </div>
                <h3><?php echo translate("Password Settings"); ?></h3>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Password"); ?></label>
                    <input type="password" name="password" placeholder="<?php echo translate("Enter new password"); ?>">
                </div>
                <div class="formContainerSeparator">
                    <label class="stdLabelWidth"><?php echo translate("Password (repeat)"); ?></label>
                    <input type="password" name="password_r" placeholder="<?php echo translate("Repeat new password"); ?>">
                </div>
                <input type="submit" name="edit_user" class="button spacingTop spacingRight" value="<?php echo translate("Save Changes"); ?>">
            </form>
    	</div>
    </div>
    <?php } ?>
</main>