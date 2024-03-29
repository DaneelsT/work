<main>
    <div class="container">
        <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
            <div class="panel panel-info" >
                <div style="padding-top:30px" class="panel-body" >
                    <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                    <form id="frmLogin" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                            <input id="login-username" type="text" class="form-control" name="username" value="" placeholder="Username or e-mail">
                        </div>
                        <div style="margin-bottom: 25px" class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                            <input id="login-password" type="password" class="form-control" name="password" placeholder="Password">
                        </div>
                        <div style="margin-top:10px" class="form-group">
                            <div class="col-sm-12 controls">
                                <input id="frmSubmit" class="btn btn-mg btn-primary btn-block" type="submit" name="submit" value="Sign In">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
