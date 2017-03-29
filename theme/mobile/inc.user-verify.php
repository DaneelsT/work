<div class="container" role="main">
    <div class="row">
        <?php
        use \Carbon\Application\Application;
        $app = Application::getInstance();

        if($this->invalidToken()) {
        ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Invalid token"); ?>!</h3>
            </div>
            <div class="panel-body">
                <p><?php echo translatevar("The token you have supplied (%s) does not appear to be valid", $app->getRouter()->getSegment(2)); ?></p>
            </div>
        </div>
        <?php }elseif($this->userExists()) { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("User already exists"); ?></h3>
            </div>
            <div class="panel-body">
                <p><?php echo translate("A user with the supplied username or email already exists in the system"); ?>!</p>
            </div>
        </div>
        <?php }elseif($this->userAdded()) { ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Account created"); ?>!</h3>
            </div>
            <div class="panel-body">
                <p><?php echo translate("Your account has been created successfully"); ?>!</p>
                <a href="<?php placeHttpRoot(); ?>login/" class="btn btn-primary"><?php echo translate("Proceed to the login page"); ?></a>
            </div>
        </div>
        <?php }elseif($this->invalidInput()) { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Invalid Input"); ?></h3>
            </div>
            <div class="panel-body">
                <p>
                    <?php echo translate("Some fields were left empty or were not filled in correctly"); ?>
                </p>
                <p>
                    <a href="<?php echo $app->getRouter()->getSegment(2); ?>" class="btn btn-primary"><?php echo translate("Go back and try again"); ?></a>
                </p>
            </div>
        </div>
        <?php }elseif($this->isVerified()) { ?>
        <div class="alert alert-danger" style="margin-top: 20px;" role="alert">
            <h4 style="margin-top: 10px;"><?php echo translate("Your password will be encrypted upon registration. Nobody will be able to view or decrypt your password"); ?>.</h4>
        </div>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Finish your registration"); ?></h3>
            </div>
            <div class="panel-body">
                <h4><?php echo translate("Basic information"); ?></h4>

                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Name'); ?></span>
                        <input type="text" class="form-control" name="user_name" />
                    </div>

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Surname'); ?></span>
                        <input type="text" class="form-control" name="user_surname" />
                    </div>

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Username'); ?></span>
                        <input type="text" class="form-control" name="user_username" />
                    </div>

                    <div class="input-group col-sm-4 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('E-mail'); ?></span>
                        <input type="text" class="form-control" name="user_email" value="<?php echo $this->getEmail(); ?>" disabled>
                    </div>

                    <div class="radio">
                        <strong><?php echo translate('Gender'); ?></strong>
                        <label class="radio-inline"><input type="radio" name="user_gender" id="male" value="1" /><?php echo translate('Male'); ?></label>
                        <label class="radio-inline"><input type="radio" name="user_gender" id="female" value="2" /><?php echo translate('Female'); ?></label>
                    </div>

                    <h4><?php echo translate("Payment Settings"); ?></h4>

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Hourly Pay'); ?></span>
                        <input type="text" class="form-control" name="hourly_pay" size="1" placeholder="<?php echo Application::getInstance()->getConfiguration("hourly_pay"); ?>">
                    </div>

                    <div class="input-group col-sm-4 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Sunday Fee'); ?></span>
                        <input type="text" class="form-control" name="sunday_fee" size="1" placeholder="<?php echo Application::getInstance()->getConfiguration("sunday_fee"); ?>">
                    </div>

                    <h4><?php echo translate("Language Settings"); ?></h4>

                    <div class="input-group col-sm-3 spacingBottom">
                        <select name="language" class="form-control">
                            <option name="nl_BE">Nederlands</option>
                            <option name="en_US">English</option>
                        </select>
                    </div>

                    <h4><?php echo translate("User password"); ?></h4>

                    <div class="input-group col-sm-4 spacingBottom">
                        <span class="input-group-addon"><?php echo translate("Password"); ?></span>
                        <input type="password" class="form-control" name="user_password_new" placeholder="<?php echo translate("Enter new password"); ?>">
                    </div>

                    <div class="input-group col-sm-5 spacingBottom">
                        <span class="input-group-addon"><?php echo translate("Password (repeat)"); ?></span>
                        <input type="password" class="form-control" name="user_password_repeat" placeholder="<?php echo translate("Repeat new password"); ?>">
                    </div>

                    <input type="submit" name="add_user" class="button spacingTop" value="<?php echo translate("Register"); ?>">
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
