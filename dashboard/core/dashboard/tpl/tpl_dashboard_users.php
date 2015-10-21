

<!-- Grid Contents -->
<div class="one-fifth" style="height:1px"></div>

<div class="three-fifths">
    <div class="box no-bg">
        <div class="inner">
            <div class="titlebar"><span class="icon awesome white user"></span> <span class="w-icon">Benutzer</span></div>

            <div class="dataTables_wrapper" role="grid">
                <div class="dataTables_length">
                    <label>
                        <select size="1" name="amount">
                            <option value="10" selected="selected">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select> Einträge pro Seite</label>
                </div>
                <div class="dataTables_filter">
                    <label>
                        Search: <input type="text" name="search">
                    </label>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Erstellt</th>
                            <th scope="col">Comments</th>
                            <th scope="col">Uploads</th>
                        </tr>
                    </thead>
                    <tbody>
                        {[user_list]}
                    </tbody>
                </table>
                <div class="dataTables_info" id="DataTables_Table_0_info">
                    Showing 1 to 10 of 57 entries
                </div>
                <div class="dataTables_paginate paging_full_numbers" id="DataTables_Table_0_paginate">
                    <a tabindex="0" class="first paginate_button paginate_button_disabled" id="DataTables_Table_0_first">
                        First
                    </a>
                    <a tabindex="0" class="previous paginate_button paginate_button_disabled" id="DataTables_Table_0_previous">
                        Previous
                    </a>
                    <span>
                        <a tabindex="0" class="paginate_active">
                            1
                        </a>
                        <a tabindex="0" class="paginate_button">
                            2
                        </a>
                        <a tabindex="0" class="paginate_button">
                            3
                        </a>
                        <a tabindex="0" class="paginate_button">
                            4
                        </a>
                        <a tabindex="0" class="paginate_button">
                            5
                        </a>
                    </span>
                    <a tabindex="0" class="next paginate_button" id="DataTables_Table_0_next">
                        Next
                    </a>
                    <a tabindex="0" class="last paginate_button" id="DataTables_Table_0_last">
                        Last
                    </a>
                </div>
            </div>

            <div class="clear"></div>
        </div>
    </div>
</div>
<div class="one-fifth"></div>
