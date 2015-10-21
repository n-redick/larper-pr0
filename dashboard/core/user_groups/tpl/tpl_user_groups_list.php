<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="/dashboard/css/bootstrap.min.css">
        <script src="/dashboard/js/jquery.min.js"></script>
        <title>WIRmachenDRUCK - Auftragsübersicht</title>
    </head>
    <body>
        {[tpl_var_start_bar]}
        <div class="col-md-12 column" style="background-image:linear-gradient(#e7e8ea,#fff);height:20px;position:fixed;top:149px;">
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
            $('.panel-heading').each(function(){
                if($(this).height() > max_height){
                    max_height = $(this).height();
                }
            });
            $('.panel-heading').height(max_height+30);
            $('.panel-heading').click(function(){
                $('.content_'+$(this).attr('data-user-id')).slideToggle();
            })
            $('.label-info').click(function(){
                console.log($(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id'));
                console.log($('#'+$(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id')).css('display'));
                if($('#'+$(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id')).css('display') == 'none'){
                    $('#'+$(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id')).html($('#'+$(this).attr('data-permission-name')).html());
                    $('#'+$(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id')).slideToggle();
                }else{
                    $('#'+$(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id')).slideToggle();
                    $('#'+$(this).attr('data-permission-name')+'_'+$(this).attr('data-user-id')).html('');
                }
            })
        </script>
    </body>
</html>
