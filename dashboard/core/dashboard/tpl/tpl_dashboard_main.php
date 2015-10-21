

                <!-- Playground Widgets -->
                <div class="full-width widget-set">
                    <div class="box no-bg">
                        <ul>
                            <li class="widget users">
                                <div class="image"></div>
                                <label>{[visitors_count]}</label>
                                <span>Visitors</span>
                            </li>
                            <li class="widget {[visitors_arrow]}">
                                <div class="image"></div>
                                <label>{[visitors_percent]}%</label>
                                <span>Visitors</span>
                            </li>
                            <li class="widget users">
                                <div class="image"></div>
                                <label>{[uploads_count]}</label>
                                <span>Uploads</span>
                            </li>
                            <li class="widget {[uploads_arrow]}">
                                <div class="image"></div>
                                <label>{[uploads_percent]}%</label>
                                <span>Uploads</span>
                            </li>
                            <li class="widget comments">
                                <div class="image"></div>
                                <label>{[comments_count]}</label>
                                <span>Comments</span>
                            </li>
                            <li class="widget {[comments_arrow]}">
                                <div class="image"></div>
                                <label>{[comments_percent]}%</label>
                                <span>Comments</span>
                            </li>
                            <li class="widget users">
                                <div class="image"></div>
                                <label>{[users_count]}</label>
                                <span>New User</span>
                            </li>
                            <li class="widget {[users_arrow]}">
                                <div class="image"></div>
                                <label>{[users_percent]}%</label>
                                <span>New User</span>
                            </li>
                            <li class="widget users">
                                <div class="image"></div>
                                <label>{[report_count]}</label>
                                <span>Reports</span>
                            </li>


                        </ul>
                    </div> 

                    <div class="clear"></div>
                    <div class="line-separator"></div>
                    <div class="clear">&nbsp;</div>

                </div> <!-- /Playground Widgets -->


                <!-- Grid Contents -->
                <div class="one-third">
                    <div class="box no-bg">
                        <h2>New Reports <a href="http://www.creepypixel.com/demos/caffeine/1.1/dashboard.html#" class="button blue tiny fright">See all open reports</a></h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Post</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                {[report_list]}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="two-thirds">
                    <div class="box">
                        <div class="inner">
                            <div class="titlebar"><span class="icon awesome white bar-chart"></span> <span class="w-icon">Monthly Visitors</span></div>
                            <div class="contents">
                                <div id="chart_dashboard" style="width: 978px; height: 300px; position: relative;" class="jqplot-target">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



