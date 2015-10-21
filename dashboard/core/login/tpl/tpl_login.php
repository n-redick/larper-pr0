<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Login</title>
        <link rel="stylesheet" href="{[_tpl_path]}/dashboard/css/bootstrap.min.css">
        <script src="{[_tpl_path]}/dashboard/js/jquery.min.js"></script>
        <script src="{[_tpl_path]}/dashboard/js/bootstrap.min.js"></script>
        <style>
            body {
                width:100px;
                height:100px;
                background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABoAAAAaCAYAAACpSkzOAAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAxMC8yOS8xMiKqq3kAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAABHklEQVRIib2Vyw6EIAxFW5idr///Qx9sfG3pLEyJ3tAwi5EmBqRo7vHawiEEERHS6x7MTMxMVv6+z3tPMUYSkfTM/R0fEaG2bbMv+Gc4nZzn+dN4HAcREa3r+hi3bcuu68jLskhVIlW073tWaYlQ9+F9IpqmSfq+fwskhdO/AwmUTJXrOuaRQNeRkOd5lq7rXmS5InmERKoER/QMvUAPlZDHcZRhGN4CSeGY+aHMqgcks5RrHv/eeh455x5KrMq2yHQdibDO6ncG/KZWL7M8xDyS1/MIO0NJqdULLS81X6/X6aR0nqBSJcPeZnlZrzN477NKURn2Nus8sjzmEII0TfMiyxUuxphVWjpJkbx0btUnshRihVv70Bv8ItXq6Asoi/ZiCbU6YgAAAABJRU5ErkJggg==);
                font-family: 'Raleway', sans-serif;
            }

            p {
                color:#CCC;
            }

            .spacing {
                padding-top:7px;
                padding-bottom:7px;
            }
            .middlePage {
                width: 565px;
                height: 500px;
                position: absolute;
                top:0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
            }

            .logo {
                color:dimgrey;
            }

            .input_margin{
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        <div class="middlePage">
            <div class="page-header">
                <h1 class="logo">Lychas <small>The Framework for Developers</small></h1>
            </div>

            <div class="tabbable" id="tabs-607514">
                <ul class="nav nav-tabs">
                    <li class="{[tab_login]}">
                        <a href="#panel-322398" data-toggle="tab">Anmelden</a>
                    </li>
                    <li class="{[tab_register]}">
                        <a href="#panel-817282" data-toggle="tab">Registrieren</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {[tab_login]}" id="panel-322398">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Bitte melden Sie sich mit Ihren Benutzerdaten an</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12" >{[tpl_login_error]}</div>
                                <div class="row">

                                    <div class="col-md-6" >
                                        <a href="#"><img src="{[_tpl_path]}/dashboard/img/fb.png" /></a><br/>
                                        <a href="#"><img src="{[_tpl_path]}/dashboard/img/tw.png" /></a><br/>
                                        <a href="#"><img src="{[_tpl_path]}/dashboard/img/gplus.png" /></a>
                                    </div>

                                    <div class="col-md-6" style="border-left:1px solid #ccc;height:160px">
                                        <form class="form-horizontal" method="post" action="{[_tpl_base_url]}?url=login">
                                            <fieldset>

                                                <input  name="username" type="text" placeholder="Benutzername" class="form-control input-md">
                                                <div class="spacing"><input type="checkbox" name="checkboxes" value="1"><small> Benutzername merken</small></div>
                                                <input  name="password" type="password" placeholder="Passwort" class="form-control input-md">
                                                <div class="spacing"><a href="#"><small> Passwort vergessen?</small></a><br/></div>
                                                <button id="singlebutton" name="login" class="btn btn-info btn-sm pull-right">Anmelden</button>


                                            </fieldset>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane {[tab_register]}" id="panel-817282">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Erstellen sie einen Benutzer-Account</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12" >{[tpl_register_error]}</div>
                                <div class="row">
                                    <form class="form-horizontal" method="post" action="{[_tpl_base_url]}?url=login&register">

                                        <div class="col-md-6" >
                                            <fieldset>

                                                <input name="username" type="text" value="{[username]}" placeholder="Benutzername" class="form-control input-md input_margin">

                                                <input name="password" type="password" value="{[password]}" placeholder="Passwort" class="form-control input-md input_margin">
                                                
                                                <input name="password_repeat" type="password"  value="{[password_repeat]}" placeholder="Passwort wiederholen" class="form-control input-md">



                                            </fieldset>
                                        </div>

                                        <div class="col-md-6" style="border-left:1px solid #ccc;height:160px">
                                            <fieldset>

                                                <input name="email" type="text" value="{[email]}" placeholder="E-Mail" class="form-control input-md input_margin">

                                                <input name="email_repeat" type="text" value="{[email_repeat]}" placeholder="E-Mail wiederholen" class="form-control input-md input_margin">

                                                <input type="submit" name="register" class="btn btn-info btn-sm pull-right" value="Registrieren">


                                            </fieldset>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
