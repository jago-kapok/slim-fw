<table class="table table-striped table-bordered table-hover">
  <tr>
    <td><strong>KK:</strong></td>
    <td><?php echo $this->contact->nkk;?></td>
    
    <td><strong>Jenis Kelamin:</strong></td>
    <td><?php echo $this->contact->gender;?></td>
  </tr>
  <tr>
    <td><strong>Tempat/Tanggal Lahir:</strong></td>
    <td><?php echo $this->contact->place_of_birth . '/' . date("d-m-Y", strtotime($this->contact->date_of_birth));?></td>

    <td><strong>Kebangsaan:</strong></td>
    <td><?php echo $this->contact->nationality;?></td>
  </tr>
  <tr>
    <td><strong>Agama:</strong></td>
    <td><?php echo $this->contact->religion;?></td>

    <td><strong>Pendidikan:</strong></td>
    <td><?php echo $this->contact->education;?></td>
  </tr>
  <tr>
    <td><strong>Pekerjaan:</strong></td>
    <td><?php echo $this->contact->work;?></td>

    <td><strong>Status Perkawinan:</strong></td>
    <td><?php echo $this->contact->marital_status;?></a></td>
  </tr>
  <tr>
    <td><strong>Kitas:</strong></td>
    <td><?php echo $this->contact->kitas;?></td>

    <td><strong>Dokumen Pasport:</strong></td>
    <td><?php echo $this->contact->pasport;?></td>
  </tr>
  <tr>
    <td><strong>Alamat Sebelumnya:</strong></td>
    <td colspan="3"><?php echo $this->contact->previous_address;?></td>
  </tr>
  <tr>
    <td><strong>Akta Perkawinan:</strong></td>
    <td><?php echo $this->contact->marriage_certificate;?></td>

    <td><strong>Tanggal Perkawinan:</strong></td>
    <td><?php echo $this->contact->date_of_marriage;?></td>
  </tr>
  <tr>
    <td><strong>Akta Perceraian:</strong></td>
    <td><?php echo $this->contact->divorce_certificate;?></td>

    <td><strong>Tanggal Perceraian:</strong></td>
    <td><?php echo $this->contact->date_of_divorce;?></td>
  </tr>
  <tr>
    <td><strong>Nama Ayah:</strong></td>
    <td><?php echo $this->contact->name_of_father;?></td>

    <td><strong>NIK Ayah:</strong></td>
    <td><?php echo $this->contact->nik_of_father;?></td>
  </tr>
  <tr>
    <td><strong>Nama Ibu:</strong></td>
    <td><?php echo $this->contact->name_of_mother;?></td>

    <td><strong>NIK Ibu:</strong></td>
    <td><?php echo $this->contact->nik_of_mother;?></td>
  </tr>
</table>