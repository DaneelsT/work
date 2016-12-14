<main>
    <div id="loginContainer">
        <form id="frmLogin" method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
            <div id="frmLoginContent">
                <input id="txtUsername" type="text" name="username"
                       placeholder="Username or e-mail" 
                       autocomplete="off" autofocus>
                <input id="txtPassword" type="password" name="password"
                       placeholder="Password" autocomplete="off">
            </div>
            <input id="frmSubmit" type="submit" name="submit" value="Sign In">
        </form>
    </div>
</main>