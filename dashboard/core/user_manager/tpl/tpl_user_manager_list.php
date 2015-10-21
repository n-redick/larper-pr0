<html>
    <head>
        <link rel="stylesheet" href="{[_tpl_path]}/dashboard/css/bootstrap.min.css">
        <script src="{[_tpl_path]}/dashboard/js/jquery.min.js"></script>
        <title>WIRmachenDRUCK - Auftrags�bersicht</title>
    </head>
    <body>
        {[tpl_var_start_bar]}
        <div class="col-md-12 column" style="background-image:linear-gradient(#e7e8ea,#fff);height:20px;position:fixed;top:149px;">
        </div>
        <div style="width:500px;height:45px;padding-left:10px;padding-top:10px;background-color:#e7e8ea;background-repeat:no-repeat;background-position: -245px -8px;position: fixed;left:58;top:100px;z-index:101;background-image: url(https://druckerei-verwaltung.de/dashboard/modules/start_leiste/tpl/N1.png)">
            <a href="{[_tpl_base_url]}?p=user_manager" class="btn btn-info" style="font-size:12px;height:26px;line-height:14px;">Zur&uuml;ck zur Benutzer&uuml;bersicht</a>
            <div style="position:absolute;top:9px;left:201px">
                <form class="form-search">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Suche Benutzer" name="q" style="padding:6px 6px;width:150px;display:none;float:left;height:28px">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div>
                </form>
            </div> 
        </div>
        <div class="container" style="position: relative;top:200px;z-index:1">
            <div class="row clearfix">
                <div class="col-md-1 column"></div>
                <div class="col-md-10 column">
                    {[tpl_content]}
                </div>
            </div>
        </div>
        <script>
            var max_height = 0;
            $('.panel-heading').each(function() {
                if ($(this).height() > max_height) {
                    max_height = $(this).height();
                }
            });
            $('.panel-heading').height(max_height + 30);
            $('.panel-heading').click(function() {
                $('.content_' + $(this).attr('data-user-id')).slideToggle();
            })
            $('.label-info').click(function() {
                console.log($(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id'));
                console.log($('#' + $(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id')).css('display'));
                if ($('#' + $(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id')).css('display') == 'none') {
                    $('#' + $(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id')).html($('#' + $(this).attr('data-permission-name')).html());
                    $('#' + $(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id')).slideToggle();
                } else {
                    $('#' + $(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id')).slideToggle();
                    $('#' + $(this).attr('data-permission-name') + '_' + $(this).attr('data-user-id')).html('');
                }
            })
        </script>
    </body>
</html>
