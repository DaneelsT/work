<div class="container" role="main">
    <div class="row">
        <?php
        use \Carbon\Application\Application;
        $user = $this->getUser();

        if( $this->userUpdated() ) { ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Account updated"); ?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo translate("Your account has been updated successfully"); ?>!</p>

                <p><a href="<?php placeHttpRoot(); ?>" class="button"><?php echo translate("Go to dashboard"); ?></a></p>
            </div>
        </div>
        <?php } if( $this->invalidInput() ) { ?>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Invalid Input"); ?></h3>
            </div>
            <div class="panel-body">
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
        <div class="col-sm-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $user->getFullName(); ?></h3>
                </div>
                <div class="panel-body">
                    <p>
                        <label class="stdLabelWidth"><?php echo translate("Username"); ?></label>
                        <?php echo $user->getUserName(); ?><br />
                        <label class="stdLabelWidth"><?php echo translate("E-mail"); ?></label>
                        <?php echo $user->getEmail(); ?><br />

                        <label class="stdLabelWidth"><?php echo translate("Gender"); ?></label>
                        <?php echo $user->getGenderString(); ?>
                    </p>

                    <p>
                   		<label class="stdLabelWidth"><?php echo translate("Hourly Pay"); ?></label>
                   		<?php echo $user->getHourlyPay(); ?><br />
                   		<label class="stdLabelWidth"><?php echo translate("Sunday Fee"); ?></label>
                   		<?php echo $user->getSundayFee(); ?>
                    </p>

                    <p>
           	            <label class="stdLabelWidth"><?php echo translate("Language"); ?></label>
                   	    <?php
                       	    if($user->getLanguage() == 'nl_BE') {
                       	        $lang = 'Nederlands';
                       	    }else{
                       	        $lang = 'English';
                       	    }
                       	    echo $lang;

                           	if($user->isAdmin()) {
                           		echo '<p><strong style="color:red;">' . translate("ADMINISTRATOR") . '</strong></p>';
                           	}
        				?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Edit my profile"); ?></h3>
                </div>
                <div class="panel-body">
            		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    	<h3><?php echo translate("General Settings"); ?></h3>

                        <div class="input-group col-sm-3 spacingBottom">
                            <span class="input-group-addon"><?php echo translate('Name'); ?></span>
                            <input type="text" class="form-control" name="user_name" value="<?php echo $user->getName(); ?>">
                        </div>

                        <div class="input-group col-sm-4 spacingBottom">
                            <span class="input-group-addon"><?php echo translate('Surname'); ?></span>
                            <input type="text" class="form-control" name="user_surname" value="<?php echo $user->getName(); ?>">
                        </div>

                        <div class="input-group col-sm-4 spacingBottom">
                            <span class="input-group-addon"><?php echo translate('Username'); ?></span>
                            <input type="text" class="form-control" disabled="disabled" name="user_username" value="<?php echo $user->getUsername(); ?>">
                        </div>

                        <div class="input-group col-sm-6 spacingBottom">
                            <span class="input-group-addon"><?php echo translate("E-mail"); ?></span>
                            <input type="text" class="form-control" name="user_email" value="<?php echo $user->getEmail(); ?>">
                        </div>

                        <div class="radio">
                            <strong><?php echo translate('Gender'); ?></strong>
                            <label class="radio-inline"><input type="radio" name="user_gender" id="male" value="1" <?php echo $maleChecked; ?> /><?php echo translate('Male'); ?></label>
                            <label class="radio-inline"><input type="radio" name="user_gender" id="female" class="spacingLeft" value="2" <?php echo $femaleChecked; ?> /><?php echo translate('Female'); ?></label>
                        </div>

                    	<h3><?php echo translate("Payment Settings"); ?></h3>

                        <div class="input-group col-sm-3 spacingBottom">
                        	<span class="input-group-addon"><?php echo translate("Hourly Pay"); ?></span>
                        	<input type="text" class="form-control" name="hourly_pay" size="1" placeholder="<?php echo Application::getInstance()->getConfiguration("hourly_pay"); ?>" value="<?php echo $user->getHourlyPay(); ?>">
                        </div>
                        
                        <div class="input-group col-sm-3 spacingBottom">
                            <span class="input-group-addon"><?php echo translate("Sunday Fee"); ?></span>
                        	<input type="text" class="form-control" name="sunday_fee" size="1" placeholder="<?php echo Application::getInstance()->getConfiguration("sunday_fee"); ?>" value="<?php echo $user->getSundayFee(); ?>">
                        </div>

                        <h3><?php echo translate("Language Settings"); ?></h3>

                        <div class="input-group col-sm-3 spacingBottom">
                            <select name="language" class="form-control">
                                <option name="nl_BE" <?php echo $nlSelected; ?>>Nederlands</option>
                                <option name="en_US" <?php echo $enSelected; ?>>English</option>
                            </select>
                        </div>

                        <h3><?php echo translate("Password Settings"); ?></h3>

                        <div class="input-group col-sm-6 spacingBottom">
                            <span class="input-group-addon"><?php echo translate("Password"); ?></span>
                            <input type="password" class="form-control" name="user_password_new" placeholder="<?php echo translate("Enter new password"); ?>">
                        </div>

                        <div class="input-group col-sm-6 spacingBottom">
                            <span class="input-group-addon"><?php echo translate("Password (repeat)"); ?></span>
                            <input type="password" class="form-control" name="user_password_repeat" placeholder="<?php echo translate("Repeat new password"); ?>">
                        </div>

                        <input type="submit" name="edit_user" class="btn btn-success" value="<?php echo translate("Save Changes"); ?>">
                    </form>
            	</div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
