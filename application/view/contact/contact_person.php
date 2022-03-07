<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<?php
			$fieldDisplay=array("No.", "Hubungan", "Sapaan", "Nama", "Mobile", "Email", "Phone", "Ext", "Fax", "Main");
			foreach($fieldDisplay as $value){
				?>
				<th><?php echo $value?></th>
				<?php
			}
			?>
		</tr>
	</thead>

	<tbody>
		<?php
		$num=1;
		foreach($this->contact_person as $key => $value) { // saldo bank
			echo "<tr>";
			echo '<td>
			<div class="dropdown">
			<button class="btn btn-info btn-xs dropdown-toggle" type="button" id="cpMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			' . $num . '
			<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="cpMenu">
			<li><a href="' . Config::get('URL') . 'contact/editcontactperson/' . $value->uid . '">Edit</a></li>
			<li role="separator" class="divider"></li>
			<li><a href="'. Config::get('URL') . 'delete/soft/contact_person/uid/' . $value->uid . '/?forward=' . $_SERVER['REQUEST_URI'] . '" onclick="return confirmation(\'Are you sure to delete?\');">Delete</a></li>
			</ul>
			</div>

			</td>';
			echo '<td>' . $value->job_title . '</td>';
			echo '<td>' . $value->salutation . '</td>';
			echo '<td>' . ucwords($value->first_name) . '</td>';
			echo '<td>' . $value->mobile . '</td>';
			echo '<td><a href="mailto:' . $value->email . '">' . $value->email  . '</a></td>';
			echo '<td>' . $value->phone . '</td>';
			echo '<td>' . $value->phone_ext . '</td>';
			echo '<td>' . $value->fax . '</td>';
			if ($value->is_main == 1) {
				echo '<td> ✔ </td>';
			} else {
				echo '<td> </td>';
			}
			echo "</tr>";
			$num++;
		} 
		?>

	</tbody>
</table>