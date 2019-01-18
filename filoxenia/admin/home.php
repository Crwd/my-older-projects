		<div class="row-fluid">
            <div class="span8 widget blue" onTablet="span7" onDesktop="span8">
                <div id="stats-chart2"  style="height:282px" ></div>
            </div>
	    </div>
        
        
        <div class="row-fluid">
            
            <div class="box black span4" onTablet="span6" onDesktop="span4">
                <div class="box-header">
                    <h2><i class="halflings-icon white user"></i><span class="break"></span>Last Users</h2>
                    <div class="box-icon">
                        <a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
                        <a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
                    </div>
                </div>
                <div class="box-content">
                    <ul class="dashboard-list metro">
                    	<?php
							$users = $stats->load_users();
							foreach($users as $user) {
								echo(
									'<li class="green">
										<strong>Username:</strong> ' . $user["username"] . '<br>
										<strong>Credits:</strong> ' . $user["credits"] . '<br>
										<strong>Last visit:</strong> ' . $user["last_visit"] . '            
									</li>'
								);
							}
						?>
                    </ul>
                </div>
            </div><!--/span-->
            
           
        
        </div>
        
        <div class="row-fluid">	

            <a class="quick-button metro yellow span2">
                <i class="icon-group"></i>
                <p>Users</p>
                <span class="badge"><?php echo $stats->count_table("users"); ?></span>
            </a>
            <a class="quick-button metro red span2">
                <i class="icon-comments-alt"></i>
                <p>Requests</p>
                <span class="badge"><?php echo $stats->count_table("cms_requests"); ?></span>
            </a>
            <a class="quick-button metro blue span2">
                <i class="icon-shopping-cart"></i>
                <p>Orders</p>
                <span class="badge"><?php echo $stats->count_table("cms_orders"); ?></span>
            </a>
            <a class="quick-button metro green span2">
                <i class="icon-barcode"></i>
                <p>Products</p>
                <span class="badge"><?php echo $stats->count_table("user_products"); ?></span>
            </a>
            <a class="quick-button metro pink span2">
                <i class="icon-envelope"></i>
                <p>Tickets</p>
                <span class="badge"><?php echo $stats->count_table("cms_tickets"); ?></span>
            </a>
            <a class="quick-button metro black span2">
                <i class="icon-calendar"></i>
                <p>Calendar</p>
            </a>
            
            <div class="clearfix"></div>
                            
        </div><!--/row-->
        
   
