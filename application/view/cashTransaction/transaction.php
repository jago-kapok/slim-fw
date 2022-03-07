<?php 
$this->renderFeedbackMessages();
// Render message success or not?>
    <div class="page-header">
    <h1>
        <?php echo $this->title; ?>
    </h1>
    </div><!-- /.page-header -->
<form class="form-horizontal" role="form" method="post" action="<?php echo URL . 'bukubesar/newTransaction/';?>">
<div class="row">
<div class="col-xs-12">
<div class="table-responsive">
						<table id="sample-table-1" class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>No</th>
									<th>Transaction Name</th>
									<th>Credit </th>
									<th>Debit </th>
									<th>Date </th>
									<th>Note</th>
								</tr>
							</thead>


							<tbody>
								<?php
								for ($i=1; $i <=10; $i++) { ?>
									<tr>
											<td>
												<?php echo $i; ?>
											</td>
											<td>
												<input type="text" name="<?php echo 'transaction_name['.$i.']'; ?>"  class="form-control align-right">
											</td>
											<td class="align-right">
												<input type="number" pattern="[0-9]+([\,|\.][0-9]+)?"
											    step="0.01"
											    placeholder="number only, max 2 decimal with dot separator(.)"
											    title="The number input must start with a number and use either comma or a dot as a decimal character." name="<?php echo 'credit['.$i.']'; ?>" id="with-step-1<?php echo $i; ?>"  class="form-control" />
											    <div id="test1-<?php echo $i; ?>"></div>
											</td>
											<td class="align-right">
												<input type="number" pattern="[0-9]+([\,|\.][0-9]+)?"
											    step="0.01"
											    placeholder="number only, max 2 decimal with dot separator(.)"
											    title="The number input must start with a number and use either comma or a dot as a decimal character." name="<?php echo 'debit['.$i.']'; ?>" id="with-step-2<?php echo $i; ?>"  class="form-control" />
											    <div id="test2-<?php echo $i; ?>"></div>
											</td>
											<td>
												<div class="input-group" style="width: 150px;">
													<input name="<?php echo 'transaction_date['.$i.']'; ?>" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd" />
													<span class="input-group-addon">
														<i class="fa fa-calendar bigger-110"></i>
													</span>
												</div>
											</td>
											<td>
												<textarea name="<?php echo 'note['.$i.']'; ?>" class="form-control"></textarea>
											</td>

											</tr>
								<?php } ?>
							</tbody>
							</table>

				</div><!-- /.table-responsive -->

					


</div><!-- /.col -->
</div><!-- /.row -->
<div class="row">
	<div class="col-xs-12">
<button type="submit" name="newbank-button" value="ok" class="btn btn-xs btn-primary" style="width: 100%;">

Save
</button>
<div class="space-10"></div>
<a href="javascript: history.go(-1)" class="btn btn-xs btn-danger" style="width: 100%;">
Cancel
</a>


</div><!-- /.col -->
</div>
</form>

<script type="text/javascript">
<?php for ($i=1; $i <=10; $i++) { ?>
$('#with-step-1<?php echo $i; ?>').on('change keyup', function(event) {
    if ( event.target.validity.valid ) {
         $('#test1-<?php echo $i; ?>').text('');
    } else {
        $('#test1-<?php echo $i; ?>').text('NOPE, Use numbers only and dot as separator (max 2)');
    }    
});
$('#with-step-2<?php echo $i; ?>').on('change keyup', function(event) {
    if ( event.target.validity.valid ) {
         $('#test2-<?php echo $i; ?>').text('');
    } else {
        $('#test2-<?php echo $i; ?>').text('NOPE, Use numbers only and dot as separator (max 2)');
    }    
});
<?php } ?>

//datepicker plugin
				//link
				$('.date-picker').datepicker({
					autoclose: true,
					todayHighlight: true
				})
				//show datepicker when clicking on the icon
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
</script>