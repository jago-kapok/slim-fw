<?php $this->renderFeedbackMessages(); 
//echo '<pre>';var_dump($this->uploaded_file);echo '</pre>';
// Render message success or not?>

<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
	    <thead>
	      <tr>
	      	<th>No</th>
	        <th>Nama</th>
	        <th>Keterangan</th>
	        <th>Delete</th>
	      </tr>
	    </thead>
	    <tbody>
	    <?php
	    $no = 1;
	    foreach ($this->uploaded_file as $key => $value) {
	      echo '<tr>
	      		<td>' . $no . '</td>
	            <td><a href="' .  Config::get('URL') . 'file/' . $value->value . '" target="_blank">' . $value->item_name . '</td>
	            <td>' . $value->note . '</td>
	            <td><a href="' .  Config::get('URL') . 'delete/soft/upload_list/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . ' " class="btn btn-danger btn-minier" onclick="return confirmation(\'Are you sure to delete?\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a></td>
	            </tr>';
	    $no++;
	    } ?>
	    </tbody>
	  </table>

	
</div><!-- /.table-responsive -->