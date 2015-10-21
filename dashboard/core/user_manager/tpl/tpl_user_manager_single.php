<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{[_tpl_path]}/dashboard/css/bootstrap.min.css">
        <script src="{[_tpl_path]}/dashboard/js/jquery.min.js"></script>
        <title>WIRmachenDRUCK - Auftrags�bersicht</title>
    </head>
    <body>
        {[tpl_var_start_bar]}
        <div class="col-md-12 column" style="background-image:linear-gradient(#e7e8ea,#fff);height:20px;position:fixed;top:149px;">
        </div>
        <div style="width:500px;height:45px;padding-left:10px;padding-top:10px;background-color:#e7e8ea;background-repeat:no-repeat;background-position: -245px -8px;position: fixed;left:58;top:100px;z-index:101;background-image: url(https://druckerei-verwaltung.de/dashboard/modules/start_leiste/tpl/N1.png)">
            <a href="{[_tpl_base_url]}?p=user_manager&a={[tpl_role]}" class="btn btn-info" style="font-size:12px;height:26px;line-height:14px;">Zur&uuml;ck zur &Uuml;bersicht</a>
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
            $('.panel-rights').click(function(){
               $('#'+$(this).attr('data-right-name')).slideToggle(); 
            });
            $('.label').click(function(){
		var element = $(this);
                $.ajax({
                    type: "POST",
                    url: "https://druckerei-verwaltung.de/dashboard/API/start_api.php?token={[tpl_user_logintoken]}",
                    data: {status:$(this).attr('data-status'),perm_id : $(this).attr('data-right-id'),user_id : $(this).attr('data-user-id'),type : 'user_manager', api_call : 'change_rights'}
                }).done(function(msg){
                    console.log('result: '+msg);
                    if(msg == '1'){

                        if(element.attr('data-status') == '1'){
				
                            element.addClass('label-warning').removeClass('label-success');
                            element.attr('data-status', '0');
                        }else{
                            element.addClass('label-success').removeClass('label-warning');
                            element.attr('data-status', '1');
                        }
                    }else{
                        alert('Änderung fehlgeschlagen!');
                    }
                })
            });
        </script>
            
    </body>
</html>
