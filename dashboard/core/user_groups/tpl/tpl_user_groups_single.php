<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="https://druckerei-verwaltung.de/dashboard/css/bootstrap.min.css">
        <script src="https://druckerei-verwaltung.de/dashboard/js/jquery.min.js"></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <title>WIRmachenDRUCK - Auftragsübersicht</title>
        <style>
            .btn-label {position: relative;left: -12px;display: inline-block;padding: 6px 12px;background: rgba(0,0,0,0.15);border-radius: 3px 0 0 3px;}
            .btn-labeled {padding-top: 0;padding-bottom: 0;}
            .btn-ico { margin-bottom:3px; }
            .ui-autocomplete {max-height: 100px;overflow-y: auto;overflow-x: hidden;}
            * html .ui-autocomplete {height: 100px;}
        </style>
    </head>
    <body>
        {[tpl_var_start_bar]}
        <div class="col-md-12 column" style="background-image:linear-gradient(#e7e8ea,#fff);height:20px;position:fixed;top:149px;">
        </div>
        <div class="container" style="position: relative;top:200px;z-index:1">
            <div class="row clearfix">
                <div class="col-md-1 column"></div>
                <div class="col-md-10 column">
                    {[tpl_user_content]}
                </div>
            </div>
        </div>
        <script>
            $('.panel-rights').click(function() {
                $('#' + $(this).attr('data-right-name')).slideToggle();
            });
            $('.label').click(function() {
                $.ajax({
                    type: "POST",
                    url: "https://druckerei-verwaltung.de/dashboard/API/start_api.php?token={[tpl_user_logintoken]}",
                    data: {status: $(this).attr('data-status'), perm_id: $(this).attr('data-right-id'), user_id: $(this).attr('data-user-id'), type: 'user_groups', api_call: 'change_rights'}
                }).done(function(msg) {
                    console.log(msg);
                    if (msg == '1') {
                        if ($(this).attr('data-status') == '1') {
                            $(this).addClass('label-warning').removeClass('label-success');
                            $(this).attr('data-status', '0');
                        } else {
                            $(this).addClass('label-success').removeClass('label-warning');
                            $(this).attr('data-status', '1');
                        }
                    } else {
                        alert('Änderung fehlgeschlagen!');
                    }
                })
            });

            $('.btn').click(function() {
                if ($(this).attr('id') == 'add_user') {
console.log("add called");
console.log("name: "+$('#names').val());
                    $.ajax({
                        type: "POST",
                    url: "https://druckerei-verwaltung.de/dashboard/API/start_api.php?token={[tpl_user_logintoken]}",
                        data: {status: '8', perm_id: $(this).attr('data-group-id'), user_id: $('#names').val(), type: 'user_groups', api_call: 'change_rights'}
                    }).done(function(msg) {
                        console.log(msg);
                        if (msg === '1') {
                            alert("Benutzer zu Gruppe hinzugefügt");
                        } else {
                            alert('Änderung fehlgeschlagen!');
                        }
                    })
                }
                if ($(this).attr('id') == 'delete_user') {
			console.log("delete called");
                    $.ajax({
                        type: "POST",
                    url: "https://druckerei-verwaltung.de/dashboard/API/start_api.php?token={[tpl_user_logintoken]}",
                        data: {status: '9', perm_id: $(this).attr('data-group-id'), user_id: $(this).attr('data-user-id'), type: 'user_groups', api_call: 'change_rights'}
                    }).done(function(msg) {
                        console.log(msg);
                        if (msg === '1') {
                            alert("Benutzer aus Gruppe entfernt");
                        } else {
                            alert('Änderung fehlgeschlagen!');
                        }
                    })
                }
            });


                {[tpl_suggest_javascript]}
        </script>

    </body>
</html>
