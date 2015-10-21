<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="{[_tpl_path]}/dashboard/css/bootstrap.min.css">
        <script src="{[_tpl_path]}/dashboard/js/jquery.min.js"></script>
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
                    <div class="row">
				<div class="col-md-3">
                                    <div class="panel panel-info" style="cursor:pointer" data-href="{[_tpl_base_url]}?p=user_manager&a=reseller">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" style="text-align:center">
						Benutzerliste
                                            </h3>
                                        </div>
                                        <img alt="300x200" style="width:100%" src="https://lorempixel.com/600/200/business" />
                                        <div class="panel-body">
                                            <div class="caption">
                                                <h3>
                                                        Reseller
                                                </h3>
                                                <p>
                                                        Accounts die an einen WIRmachenDRUCK-Reseller Shop gebunden sind.
                                                </p>
                                            </div>
                                        </div>
                                        <p>
                                            <a href="{[_tpl_base_url]}?p=user_manager&a=reseller" class="btn btn-info btn-block">Anzeigen</a>
                                        </p>
                                        <div class="panel-footer">
                                            {[reseller_entries]} Einträge
                                        </div>
                                    </div>
				</div>
				<div class="col-md-3">
                                    <div class="panel panel-info" style="cursor:pointer" data-href="{[_tpl_base_url]}?p=user_manager&a=lieferanten">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" style="text-align:center">
						Benutzerliste
                                            </h3>
                                        </div>
                                        <img alt="300x200" style="width:100%" src="https://lorempixel.com/600/200/technics" />
                                        <div class="panel-body">
                                            <div class="caption">
                                                <h3>
                                                        Lieferanten
                                                </h3>
                                                <p>
                                                    Verwaltungsaccounts unserer Produktionsstätten<br>
                                                </p>
                                            </div>
                                        </div>
                                        <p>
                                            <a href="{[_tpl_base_url]}?p=user_manager&a=lieferanten" class="btn btn-info btn-block">Anzeigen</a>
                                        </p>
                                        <div class="panel-footer">
                                            {[factory_entries]} Einträge
                                        </div>
                                    </div>
				</div>
				<div class="col-md-3">
                                    <div class="panel panel-info" style="cursor:pointer" data-href="{[_tpl_base_url]}?p=user_manager&a=user">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" style="text-align:center">
						Benutzerliste
                                            </h3>
                                        </div>
                                        <img alt="300x200" style="width:100%" src="https://lorempixel.com/600/200/people" />
                                        <div class="panel-body">
                                            <div class="caption">
                                                <h3>
                                                    Mitarbeiter
                                                </h3>
                                                <p>
                                                    WIRmachenDRUCK Mitarbeiter Accounts<br>
                                                </p>
                                            </div>
                                        </div>
                                        <p>
                                            <a href="{[_tpl_base_url]}?p=user_manager&a=user" class="btn btn-info btn-block">Anzeigen</a>
                                        </p>
                                        <div class="panel-footer">
                                            {[worker_entries]} Einträge
                                        </div>
                                    </div>
				</div>
                        	<div class="col-md-3">
                                    <div class="panel panel-info" style="cursor:pointer" data-href="{[_tpl_base_url]}?p=user_manager&a=all">
                                        <div class="panel-heading">
                                            <h3 class="panel-title" style="text-align:center">
						Benutzerliste
                                            </h3>
                                        </div>
                                        <img alt="300x200" style="width:100%" src="https://lorempixel.com/600/200/abstract" />
                                        <div class="panel-body">
                                            <div class="caption">
                                                <h3>
                                                        Gesamtübersicht
                                                </h3>
                                                <p>
                                                    Alle Accounts, nach Typ sortiert<br>
                                                </p>
                                            </div>
                                        </div>
                                        <p>
                                                    <a href="{[_tpl_base_url]}?p=user_manager&a=all" class="btn btn-info btn-block">Anzeigen</a>
                                        </p>
                                        <div class="panel-footer">
                                            {[all_entries]} Einträge
                                        </div>
                                    </div>
				</div>
			</div>
                </div>
                <div class="col-md-1 column"></div>
                <script>
                    var max_height = 0;
                    $('.panel-body').each(function(){
                        if($(this).height() > max_height){
                            max_height = $(this).height();
                        }
                    });
                    $('.panel-body').height(max_height+30);
                    
                    $('.panel-info').click(function(){
                       window.document.location = $(this).attr('data-href'); 
                    });
                </script>
            </div>
        </div>
    </body>
</html>

