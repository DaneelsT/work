<main>
    <?php if($this->inviteSent()) { ?>
        <div class="panel panelSeparatorTop">
            <div class="panelContent">
                <h2 style="color:#2FBC2F"><?php echo translate("Invite sent"); ?>!</h2>

                <p><?php echo translate("An invite has been sent to this user"); ?>!</p>
            </div>
        </div>
    <?php }elseif($this->invalidEmail()) { ?>
        <div class="panel panelSeparatorTop">
            <div class="panelContent">
                <h2 style="color:#BC1717"><?php echo translate("Invalid email entered"); ?>!</h2>

                <p><?php echo translate("The email you have entered is not valid"); ?>!</p>
            </div>
        </div>
    <?php } ?>
    <div class="panel panelSeparatorTop">
        <div class="panelContent">
            <h2><?php echo translate("Invite a user"); ?></h2>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <div>
                <label class="stdLabelWidth spacingBottom"><?php echo translate("E-mail"); ?>: </label>
                <input type="text" name="user_email" placeholder="johndoe@mail.com">
            </div>
            <div>
                <label class="stdLabelWidth"><?php echo translate("Name"); ?>: </label>
                <input type="text" name="user_name" placeholder="John Doe">
            </div>
            <div>
                <label class="stdLabelWidth"><?php echo translate("Language"); ?></label>
                <select name="language">
                    <option name="nl_BE" value="nl_BE" <?php echo $nlSelected; ?>>Nederlands</option>
                    <option name="en_US" value="en_US" <?php echo $enSelected; ?>>English</option>
                </select>
            </div>
            <input type="submit" class="button spacingTop" name="send_invite" value="<?php echo translate("Send Invite"); ?>">
            </form>
        </div>
    </div>
</main>
