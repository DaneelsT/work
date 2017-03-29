<div class="container" role="main">
    <div class="row">
        <?php if( $this->userAdded() ) { ?>
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><h3><?php echo translate("User added"); ?></h3>
                </div>
                <div class="panel-body">
                    <p><?php echo translate("User successfully added to the Work Webinterface"); ?>.</p>
                    <p><a href="/users" class="btn btn-primary"><?php echo translate("Return to user overview"); ?></a></p>
                </div>
            </div>
        <?php } elseif( $this->invalidInput() ) { ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("Invalid input"); ?></h3>
                </div>
                <div class="panel-body">
                    <p><?php echo translate("Some fields were not filled in (correctly). Fill in all fields correctly and try again"); ?>.</p>
                </div>
            </div>
        <?php } elseif( $this->existingUser() ) { ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo translate("User already exists"); ?></h3>
                </div>
                <div class="panel-body">
                    <p><?php echo translate("A user with this username and/or email already exists"); ?>!</p>
                </div>
            </div>
        <?php } ?>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo translate("Add a new user"); ?></h3>
            </div>
            <div class="panel-body">
                <h4><?php echo translate("Basic information"); ?></h4>

                <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                	<input type="hidden" name="user_ip" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>">

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Username'); ?></span>
                        <input type="text" class="form-control" name="user_username" id="user_username" placeholder="johndoe" />
                    </div>

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Name'); ?></span>
                        <input type="text" class="form-control" name="user_name" id="user_name" placeholder="John" />
                    </div>

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Surname'); ?></span>
                        <input type="text" class="form-control" name="user_surname" id="user_surname" placeholder="Doe" />
                    </div>

                    <div class="input-group col-sm-3 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('E-mail'); ?></span>
                        <input type="text" class="form-control" name="user_email" id="user_email" placeholder="john.doe@gmail.com" />
                    </div>

                    <div class="radio">
                        <strong><?php echo translate('Gender'); ?></strong>
                        <label class="radio-inline"><input type="radio" name="user_gender" id="male" value="1" /><?php echo translate('Male'); ?></label>
                        <label class="radio-inline"><input type="radio" name="user_gender" id="female" class="spacingLeft" value="2" /><?php echo translate('Female'); ?></label>
                    </div>

                    <h4><?php echo translate("User password"); ?></h4>

                    <div class="input-group col-sm-4 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Password'); ?></span>
                        <input type="text" class="form-control" name="user_password" id="user_password" placeholder="<?php echo translate("Enter a password"); ?>" />
                    </div>

                    <div class="input-group col-sm-4 spacingBottom">
                        <span class="input-group-addon"><?php echo translate('Password (repeat)'); ?></span>
                        <input type="text" class="form-control" name="user_password_repeat" id="user_password_repeat" placeholder="<?php echo translate("Repeat password"); ?>" />
                    </div>

                    <input type="submit" name="add_user" class="btn btn-success spacingTop" value="<?php echo translate("Add User"); ?>">
                </form>
            </div>
        </div>
    </div>
</div>
