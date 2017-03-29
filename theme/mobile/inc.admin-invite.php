<div class="container" role="main">
    <?php if($this->inviteSent()) { ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title" style="color:#2FBC2F"><?php echo translate("Invite sent"); ?>!</h3>
            </div>
            <div class="panel-body">
                <p><?php echo translate("An invite has been sent to this user"); ?>!</p>
            </div>
        </div>
    <?php }elseif($this->invalidEmail()) { ?>
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h3 class="panel-title" style="color:#BC1717"><?php echo translate("Invalid email entered"); ?>!</h3>
            </div>
            <div class="panel-body">
                <p><?php echo translate("The email you have entered is not valid"); ?>!</p>
            </div>
        </div>
    <?php } ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo translate("Invite a user"); ?></h3>
        </div>
        <div class="panel-body">
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

                <div class="input-group col-sm-4 spacingBottom">
                    <span class="input-group-addon"><?php echo translate('E-mail'); ?></span>
                    <input type="email" class="form-control" required name="user_email" placeholder="johndoe@mail.com" />
                </div>

                <div class="input-group col-sm-3 spacingBottom">
                    <span class="input-group-addon"><?php echo translate('Name'); ?></span>
                    <input type="text" class="form-control" name="user_name" placeholder="John Doe" />
                </div>

                <input type="submit" class="btn btn-success" name="send_invite" value="<?php echo translate("Send Invite"); ?>">
            </form>
        </div>
    </div>
</div>
