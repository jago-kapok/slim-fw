<div class="main-content">
	<div class="main-content-inner">
		<div class="page-content">
			<div class="tabbable">
				<ul class="nav nav-tabs" id="myTab">
					<?php
						if (!empty($this->raw_mat)) {
							echo '<li class="active">
									<a data-toggle="tab" href="#raw_mat">
										Raw Material
									</a>
								</li>';
						}

						if (!empty($this->wip)) {
							echo '<li>
									<a data-toggle="tab" href="#wip">
										WIP
									</a>
								</li>';
						}

						if (!empty($this->finish_goods)) {
							echo '<li>
									<a data-toggle="tab" href="#finish_goods">
										Finished Product
									</a>
								</li>';
						}

						if (!empty($this->trading_goods)) {
							echo '<li>
									<a data-toggle="tab" href="#trading_goods">
										Trading Goods
									</a>
								</li>';
						}

						if (!empty($this->tools)) {
							echo '<li>
									<a data-toggle="tab" href="#tools">
										Production Resources & Tools
									</a>
								</li>';
						}

						if (!empty($this->operating_supplies)) {
							echo '<li>
									<a data-toggle="tab" href="#operating_supplies">
										Operating Supplies
									</a>
								</li>';
						}

						if (!empty($this->asset)) {
							echo '<li>
									<a data-toggle="tab" href="#asset">
										Asset
									</a>
								</li>';
						}
					?>
				</ul>

				<div class="tab-content">
					<div id="raw_mat" class="tab-pane in active">
						<?php include('inventory_valuation_raw_mat.php'); ?>
					</div>

					<div id="wip" class="tab-pane">
						<?php include('inventory_valuation_wip.php'); ?>
					</div>

					<div id="finish_goods" class="tab-pane">
						<?php include('inventory_valuation_finish_goods.php'); ?>
					</div>

					<div id="trading_goods" class="tab-pane">
						<?php include('inventory_valuation_trading_goods.php'); ?>
					</div>

					<div id="tools" class="tab-pane">
						<?php include('inventory_valuation_tools.php'); ?>
					</div>

					<div id="operating_supplies" class="tab-pane">
						<?php include('inventory_valuation_operating_supplies.php'); ?>
					</div>

					<div id="asset" class="tab-pane">
						<?php include('inventory_valuation_asset.php'); ?>
					</div>
				</div>
			</div>
			<br>
			&nbsp;
			<br>
			<table class="table table-striped table-bordered table-hover ExcelTable2007">
				<tr class="info">
					<td colspan="2" class="text-center">REKAP</td>
				</tr>
				<tr>
					<td width="75%">Total Valuation Raw Material</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_raw_mat, 0); ?></td>
				</tr>
				<tr>
					<td width="75%">Total Valuation WIP</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_wip, 0); ?></td>
				</tr>
				<tr>
					<td width="75%">Total Finished Product Valuation</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_finish_goods, 0); ?></td>
				</tr>
				<tr>
					<td width="75%">Total Trading Goods Valuation</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_trading_goods, 0); ?></td>
				</tr>
				<tr>
					<td width="75%">Total Tools Valuation</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_tools, 0); ?></td>
				</tr>
				<tr>
					<td width="75%">Total Operating Supplies Valuation</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_operating_supplies, 0); ?></td>
				</tr>
				<tr>
					<td width="75%">Total Asset Valuation</td>
					<td width="25%" class="text-right"><?php echo number_format($saldo_idr_operating_supplies, 0); ?></td>
				</tr>
				<tr class="success">
					<td width="75%">Total All Valuation</td>
					<td width="25%" class="text-right"><?php echo number_format(($saldo_idr_asset + $saldo_idr_operating_supplies + $saldo_idr_tools + $saldo_idr_trading_goods + $saldo_idr_finish_goods + $saldo_idr_wip + $saldo_idr_raw_mat), 0); ?></td>
				</tr>
			</table>
		</div><!-- /.page-content -->
	</div><!-- /.main-content-inne -->
</div><!-- /.main-content -->