<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="{[_tpl_path]}/dashboard/css/bootstrap.min.css">
    </head>
    <body>
        <div id="login_wrapper">
            <div><img alt="background-cmyk-stripes" id="login_background" src="{[_tpl_path]}/dashboard/img/background.png" /></div>
            <div id="login_logo"></div>
            <div id="login_box">
                <form name="login_form" method="post" action="{[_tpl_base_url]}?url=login&register">
                    <div id="login_headline">Benutzerregistrierung</div>
                    <div id="login_content">
                        <div id="login_label">Benutzer</div>
                        <div id="login_field"><input type="text" name="username" /></div>
                        <div class="clear"></div>
                        <div id="login_label">Passwort</div>
                        <div id="login_field"><input type="password" name="password" /></div>
                        <div id="login_label">Passwort wiederholen</div>
                        <div id="login_field"><input type="password" name="password_repeat" /></div>
                        <div id="login_label">E-Mail</div>
                        <div id="login_field"><input type="text" name="email" /></div>
                        <div id="login_label">E-Mail wiederholen</div>
                        <div id="login_field"><input type="text" name="email_repeat" /></div>
                        <div class="clear"></div>
                    </div>
                    <div id="login_footer">
                        <input type="submit" name="register" value="Einloggen" />
                    </div>
                </form>
            </div>
            {[tpl_login_error]}
        </div>
    </body>
</html>
