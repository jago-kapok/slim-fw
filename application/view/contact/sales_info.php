    	<div class="row">
			    	<div class="col-xs-12 col-sm-6 widget-container-col">
                <div class="widget-box">
                    <!-- /section:custom/widget-box.options -->
                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            <table class="table table-bordered table-hover table-striped">
				    				<tr>
				    					<td><i class="icon-user"></i> Account Manager</td>
				    					<td>
				    						<?php echo ucwords(strtolower($this->contact->sales_account_manager)); ?>
				    					</td>
				    				</tr>

				    				<tr>
				    					<td><i class="icon-user"></i> Account Officer</td>
				    					<td>
				    						<?php echo ucwords(strtolower($this->contact->sales_account_executive)); ?>
				    					</td>
				    				</tr>

				    				<tr>
				    					<td>Lead Source</td>
				    					<td>
				    						<?php echo $this->contact->sales_lead_source; ?>
				    					</td>
				    				</tr>

				    			</table>
                        </div>
                    </div>
                </div>
            </div><!-- /.col-sm-6 -->
		</div>