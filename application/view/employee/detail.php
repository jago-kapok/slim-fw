<div class="main-content">
  <div class="main-content-inner">
    <!-- #section:basics/content.breadcrumbs -->
    <div class="breadcrumbs" id="breadcrumbs">
      <script type="text/javascript">
        try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
      </script>

      <ul class="breadcrumb">
      <li>
              <div class="btn-group btn-corner">
                  <a role="button" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="btn btn-minier btn-inverse">
                    <span class="glyphicon glyphicon-edit" aria-hidden="true" aria-label="edit"></span> Edit Profile
                  </a>

                  <a  href="<?php echo Config::get('URL') . 'employee/attendanceReport/' . date('Y-m-d') . '/' . $this->contact->uid . '/'; ?>"  class="btn btn-minier btn-info"><span class="glyphicon glyphicon-time"></span> Absensi</a>

                  <a href="<?php echo Config::get('URL') . 'user/editAvatar/' . $this->contact->uid . '/'; ?>" class="btn btn-minier btn-success"><span class="glyphicon glyphicon-user" aria-hidden="true"></span> Ganti Photo</a>
                </div>
        </li>
      </ul><!-- /.breadcrumb -->

    <!-- #section:basics/content.searchbox -->
    <div class="nav-search" id="nav-search">
      <form class="form-search" method="get" action="<?php echo Config::get('URL') . 'employee/index/';?>">
        <span class="input-icon">
          <input type="text" placeholder="Search ..." class="nav-search-input" id="nav-search-input" autocomplete="off" name="find" value="<?php if(isset($_GET['find'])){ echo $_GET['find'];}?>" />
          <i class="ace-icon fa fa-search nav-search-icon"></i>
        </span>
      </form>
    </div><!-- /.nav-search -->

    <!-- /section:basics/content.searchbox -->
    </div>

  <!-- /section:basics/content.breadcrumbs -->
  <div class="page-content">
    <div class="row">
      <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="collapse" id="editContact">
          <div class="panel panel-primary">
            <div class="panel-heading">
              <h3 class="panel-title">
                Edit Profile
              </h3>
            </div>
            <form method="post" action="<?php echo Config::get('URL') . 'employee/updateEmployee/' . $this->contact->user_name; ?>">
              <table class="table table-bordered table-hover table-striped ">
                <tr>
                  <td><strong>Nama:</strong></td>
                  <td>
                    <input type="text" name="full_name" class="form-control" value="<?php echo $this->contact->full_name;?>">
                  </td>
                  <td><strong>Jenis Kelamin:</strong></td>
                  <td>
                    <select name="gender" class="form-control">
                      <option value="perempuan" <?php if ($this->contact->gender == 'perempuan') {
                        echo 'selected';
                      } ?>>Perempuan</option>
                      <option value="laki-laki" <?php if ($this->contact->gender == 'laki-laki') {
                        echo 'selected';
                      } ?>>Laki - Laki</option>
                    </select>
                  </td>  
                </tr>      


                <tr>
                  <td><strong>Alamat Sekarang:</strong></td>
                  <td colspan="3"><input type="text" class="form-control" name="address_street" value="<?php echo $this->contact->address_street;?>"></td>
                </tr>
                <tr>
                  <td><strong>Alamat Sesuai KTP:</strong></td>
                  <td colspan="3"><input type="text" class="form-control" name="previous_address" value="<?php echo $this->contact->previous_address;?>"></td>
                </tr>
                <tr>
                  <td><strong>Kota:</strong></td>
                  <td><input type="text" name="address_city" class="form-control" value="<?php echo $this->contact->address_city;?>"></td>

                  <td><strong>Propinsi:</strong></td>
                  <td><input type="text" name="address_state" class="form-control" value="<?php echo $this->contact->address_state;?>"></td>
                </tr>

                <tr>
                 <td><strong>Zip:</strong></td>
                 <td><input type="text" name="address_zip" class="form-control" value="<?php echo $this->contact->address_zip;?>"></td>

                 <td><strong>Email:</strong></td>
                 <td><input type="text" name="email" class="form-control" value="<?php echo $this->contact->email;?>"></td>
               </tr>
               <tr>
                 <td><strong>Social Media:</strong></td>
                 <td><input type="text" name="website" class="form-control" value="<?php echo $this->contact->website;?>"></td>
               </tr>
               <tr>
                <td><strong>Phone:</strong></td>
                <td><input type="text" name="phone" class="form-control" value="<?php echo $this->contact->phone; ?>"></td>
                <td><strong>Fax:</strong></td>
                <td><input type="text" name="fax" class="form-control" value="<?php echo $this->contact->fax; ?>"></td>
              </tr>

              <tr>

                <td><strong>NIK:</strong></td>
                <td><input type="text" name="nik" class="form-control" value="<?php echo $this->contact->nik;?>"></td>

                <td><strong>Nomer KK:</strong></td>
                <td><input type="text" name="nkk" class="form-control" value="<?php echo $this->contact->nkk;?>"></td>
              </tr>

              <?php if (Auth::isPermissioned('director,hr')) { ?>
              <tr>
                <td><strong>Department:</strong></td>
                <td>
                    <select name="department">
                      <option value="">Pilih</option>
                      <option value="director" <?php if($this->contact->department == 'director') echo "selected=selected";?>>Director</option>
                      <option value="management" <?php if($this->contact->department == 'management') echo "selected=selected";?>>Management</option>
                      <option value="hr" <?php if($this->contact->department == 'hr') echo "selected=selected";?>>Human Resources</option>
                      <option value="finance" <?php if($this->contact->department == 'finance') echo "selected=selected";?>>Finance</option>
                      <option value="ppic" <?php if($this->contact->department == 'ppic') echo "selected=selected";?>>PPIC</option>
                      <option value="purchasing" <?php if($this->contact->department == 'purchasing') echo "selected=selected";?>>Purchasing</option>
                      <option value="sales" <?php if($this->contact->department == 'sales') echo "selected=selected";?>>Sales</option>
                      <option value="production" <?php if($this->contact->department == 'production') echo "selected=selected";?>>Production</option>
                      <option value="qc" <?php if($this->contact->department == 'qc') echo "selected=selected";?>>QC</option>
                    </select>
                </td>

                <td><strong>Grade:</strong></td>
                <td>
                  <select name="grade">
                    <option value="">Pilih</option>
                    <option value="900" <?php if($this->contact->grade >= 900) echo "selected=selected";?>>Director</option>
                    <option value="600" <?php if($this->contact->grade == 600) echo "selected=selected";?>>Senior Manager</option>
                    <option value="500" <?php if($this->contact->grade == 500) echo "selected=selected";?>>Manager</option>
                    <option value="400" <?php if($this->contact->grade == 400) echo "selected=selected";?>>Supervisor</option>
                    <option value="300" <?php if($this->contact->grade == 300) echo "selected=selected";?>>Officer</option>
                    <option value="200" <?php if($this->contact->grade == 200) echo "selected=selected";?>>Staff</option>
                    <option value="100" <?php if($this->contact->grade == 100) echo "selected=selected";?>>intern</option>
                  </select>
                </td>
              </tr>

              <tr>
                <td><strong>Salary:</strong></td>
                <td><input type="number" name="salary" class="form-control" value="<?php echo $this->contact->salary;?>"></td>

                <td><strong>Rekening:</strong></td>
                <td>
                  <input type="text" name="bank_saving_account" class="form-control" value="<?php echo $this->contact->bank_saving_account;?>">
                </td>
              </tr>

              <tr>
                 <td><strong>Jam Kerja:</strong></td>
                <td>
                    <select name="working_time">
                      <option value="">Pilih</option>
                      <?php
                        foreach ($this->jam_kerja as $key => $value) {
                          if ($this->contact->working_time == $value->group) {
                            echo '<option value="' . $value->group . '" selected="selected">' . $value->group . '</option>';
                          } else {
                            echo '<option value="' . $value->group . '">' . $value->group . '</option>';
                          }
                        }
                        

                      ?>
                    </select>
                </td>

                <td><strong></strong></td>
                <td>
                </td>
              </tr>

             <?php } ?>

              


              <tr>
                <td><strong>Tempat/Tanggal Lahir:</strong></td>
                <td><input type="text" name="place_of_birth" value="<?php echo $this->contact->place_of_birth;?>"> / <input type="text" name="date_of_birth" class="datepicker" value="<?php echo $this->contact->date_of_birth;?>" data-date-format="yyyy-mm-dd"></td>



                <td><strong>Kebangsaan:</strong></td>
                <td><input type="text" name="nationality" class="form-control" value="<?php echo $this->contact->nationality;?>"></td>
              </tr>

              <tr>
                <td><strong>Agama:</strong></td>
                <td><input type="text" name="religion" class="form-control" value="<?php echo $this->contact->religion;?>"></td>

                <td><strong>Pendidikan:</strong></td>
                <td><input type="text" name="education" class="form-control" value="<?php echo $this->contact->education;?>"></td>
              </tr>

              <tr>
                <td><strong>Pekerjaan Sebelumnya:</strong></td>
                <td><input type="text" name="previous_work" class="form-control" value="<?php echo $this->contact->previous_work;?>"></td>

                <td><strong>Status Perkawinan:</strong></td>
                <td><input type="text" name="marital_status" class="form-control" value="<?php echo $this->contact->marital_status;?>"></td>
              </tr>

              <tr>
                <td><strong>KITAS:</strong></td>
                <td><input type="text" name="kitas" class="form-control" value="<?php echo $this->contact->kitas;?>"></td>

                <td><strong>Pasport:</strong></td>
                <td><input type="text" name="pasport" class="form-control" value="<?php echo $this->contact->pasport;?>"></td>
              </tr>

              <tr>
                <td><strong>Akta Perkawinan:</strong></td>
                <td><input type="text" name="marriage_certificate" class="form-control" value="<?php echo $this->contact->marriage_certificate;?>"></td>

                <td><strong>Tanggal Perkawinan:</strong></td>
                <td>
                  <input type="text" name="date_of_marriage" class="form-control datepicker" value="<?php echo $this->contact->date_of_marriage;?>" data-date-format="yyyy-mm-dd">
                </td>
              </tr>

              <tr>
                <td><strong>Akta Perceraian:</strong></td>
                <td><input type="text" name="divorce_certificate" class="form-control" value="<?php echo $this->contact->divorce_certificate;?>"></td>

                <td><strong>Tanggal Perceraian:</strong></td>
                <td>
                  <input type="text" name="date_of_divorce" class="form-control datepicker" value="<?php echo $this->contact->date_of_divorce;?>" data-date-format="yyyy-mm-dd"></td>
                </tr>

                <tr>
                  <td><strong>Nama Ayah:</strong></td>
                  <td><input type="text" name="name_of_father" class="form-control" value="<?php echo $this->contact->name_of_father;?>"></td>

                  <td><strong>NIK Ayah:</strong></td>
                  <td><input type="text" name="nik_of_father" class="form-control" value="<?php echo $this->contact->nik_of_father;?>"></td>
                </tr>

                <tr>
                  <td><strong>Nama Ibu:</strong></td>
                  <td><input type="text" name="name_of_mother" class="form-control" value="<?php echo $this->contact->name_of_mother;?>"></td>

                  <td><strong>NIK Ibu:</strong></td>
                  <td><input type="text" name="nik_of_mother" class="form-control" value="<?php echo $this->contact->nik_of_mother;?>"></td>
                </tr>
                <tr>
                  <td><strong>Catatan Tambahan:</strong></td>
                  <td colspan="3"><textarea  class="form-control" name="note"><?php echo $this->contact->note;?></textarea></td>
                </tr>

              </table>

              <div class="panel-footer">


                <a class="btn btn-sm btn-danger" data-toggle="collapse" href="#editContact" aria-expanded="false" aria-controls="collapseExample" class="pull-right">
                  <span class="glyphicon glyphicon-remove" aria-hidden="true" aria-label="edit"></span>
                  Cancel
                </a>


                <button class="btn btn-sm btn-primary">
                  <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true" aria-label="save"></span>
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>

        <?php $this->renderFeedbackMessages();?>

        <div class="row">
          <div class="col-xs-12 col-sm-4">
            <span class="profile-picture">
              <img id="avatar" class="editable img-responsive" src='<?= Config::get('URL') . 'avatars/' . $this->contact->uid . '_medium.jpg'; ?>' />
            </span>
          </div><!-- /.col-sm-6 -->


          <div class="col-xs-12 col-sm-8">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title">
                  <?php echo strtoupper($this->contact->user_name) . '/' . $this->contact->uid;?>
                </h3>
              </div>
              <table class="table table-striped table-bordered table-hover">
                <tr>
                  <td><strong>Nama:</strong></td>
                  <td colspan="3"><?php echo strtoupper($this->contact->full_name);?></td>
                </tr>
                <tr>
                  <td><strong>Alamat:</strong></td>
                  <td colspan="3"><?php echo $this->contact->address_street;?></td>
                </tr>
                <tr>
                  <td><strong>Kota:</strong></td>
                  <td><?php echo $this->contact->address_city;?></td>

                  <td><strong>Propinsi:</strong></td>
                  <td><?php echo $this->contact->address_state;?></td>
                </tr>
                <tr>
                  <td><strong>Zip:</strong></td>
                  <td><?php echo $this->contact->address_zip;?></td>

                  <td><strong>Phone:</strong></td>
                  <td><?php echo $this->contact->phone;?></td>

                </tr>
                <tr>
                  <td><strong>Email:</strong></td>
                  <td><?php echo $this->contact->email;?></td>


                  <td><strong>Socmed:</strong></td>
                  <td><?php echo $this->contact->website;?></td>
                </tr>

              </table>
            </div>
          </div><!-- /.col-sm-6 -->
        </div><!-- ./row -->


        <div class="row">
          <div class="col-xs-12 col-sm-12">

            <div class="tabbable">
                      <ul class="nav nav-tabs" id="myTab">
                        <li class="active">
                          <a data-toggle="tab" href="#home">
                            <i class="green ace-icon fa fa-home bigger-120"></i>
                            Data Pribadi
                          </a>
                        </li>

                        <li>
                          <a data-toggle="tab" href="#note">
                            <i class="green ace-icon fa fa-comments bigger-120"></i>
                            Catatan
                          </a>
                        </li>

                        <li>
                          <a data-toggle="tab" href="#uploaded-file">
                            <i class="green ace-icon fa fa-cloud-download bigger-120"></i>
                            Uploaded File
                          </a>
                        </li>

                        <li>
                          <a data-toggle="tab" href="#log">
                            <i class="green ace-icon fa fa-undo bigger-120"></i>
                            Logs
                          </a>
                        </li>

                        <li class="dropdown">
                          <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="glyphicon glyphicon-plus text-danger"></i>
                            &nbsp;
                            <i class="ace-icon fa fa-caret-down bigger-110 width-auto"></i>
                          </a>

                          <ul class="dropdown-menu dropdown-info">
                            <li>
                              <a data-toggle="tab" href="#dropdown1">Upload Photo/Scan</a>
                            </li>

                            <li>
                              <a data-toggle="tab" href="#dropdown2">Upload Document</a>
                            </li>
                          </ul>
                        </li>
                      </ul>

                      <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                          <table class="table table-striped table-bordered table-hover">
                            <tr>
                              <td><strong>Department:</strong></td>
                              <td><?php echo strtoupper($this->contact->department);?></td>

                              <td><strong>Grade:</strong></td>
                              <td>
                                <?php
                                  switch ($this->contact->grade) {
                                      case 900:
                                          echo "Director";
                                          break;
                                      case 600:
                                          echo "Senior Manager";
                                          break;  
                                      case 500:
                                          echo "Manager";
                                          break;
                                      case 400:
                                          echo "Supervisor";
                                          break;
                                      case 300:
                                          echo "Officer";
                                          break;
                                      case 200:
                                          echo "Staff";
                                          break;
                                      case 100:
                                          echo "intern";
                                          break;
                                  } ?>

                              </td>
                            </tr>
                            <tr>
                              <td><strong>Salary:</strong></td>
                              <td><?php echo number_format($this->contact->salary);?></td>

                              <td><strong>Rekening:</strong></td>
                              <td><?php echo $this->contact->bank_saving_account;?></td>
                            </tr>
                            <tr>
                              <td><strong>Jenis Kelamin:</strong></td>
                              <td><?php echo $this->contact->gender;?></td>
                            </tr>
                            <tr>
                              <td><strong>NIK:</strong></td>
                              <td><?php echo $this->contact->nik;?></td>

                              <td><strong>KK:</strong></td>
                              <td><?php echo $this->contact->nkk;?></td>

                            </tr>
                            <tr>
                              <td><strong>Tempat/Tanggal Lahir:</strong></td>
                              <td><?php echo $this->contact->place_of_birth . '/' . date("d M, Y", strtotime($this->contact->date_of_birth));?></td>

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
                              <td><strong>Pekerjaan Sebelumnya:</strong></td>
                              <td><?php echo $this->contact->previous_work;?></td>

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
                              <td><strong>Alamat Sesuai KTP:</strong></td>
                              <td colspan="3"><?php echo $this->contact->previous_address;?></td>
                            </tr>
                            <tr>
                              <td><strong>Akta Perkawinan:</strong></td>
                              <td><?php echo $this->contact->marriage_certificate;?></td>

                              <td><strong>Tanggal Perkawinan:</strong></td>
                              <td><?php echo date("d M, Y", strtotime($this->contact->date_of_marriage));?></td>
                            </tr>
                            <tr>
                              <td><strong>Akta Perceraian:</strong></td>
                              <td><?php echo $this->contact->divorce_certificate;?></td>

                              <td><strong>Tanggal Perceraian:</strong></td>
                              <td><?php echo date("d M, Y", strtotime($this->contact->date_of_divorce));?></td>
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
                        </div>

                        <div id="note" class="tab-pane fade">
                          <ol reversed><?php echo $this->contact->note;?></ol>
                        </div>

                        <div id="uploaded-file" class="tab-pane fade">
                          <table class="table table-striped table-bordered table-hover">
                            <thead>
                              <tr>
                                <th>Nama</th>
                                <th>Keterangan</th>
                                <th>Delete</th>
                              </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($this->uploaded_file as $key => $value) {
                              echo '<tr>
                                    <td><a href="' .  Config::get('URL') . 'file/' . $value->value . '" target="_blank">' . $value->item_name . '</td>
                                    <td>' . $value->note . '</td>
                                    <td><a href="' .  Config::get('URL') . 'delete/soft/upload_list/uid/' . $value->uid . '/&forward=' . $_SERVER['REQUEST_URI'] . ' " class="btn btn-danger btn-minier" onclick="return confirmation(\'Are you sure to delete?\');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> delete</a></td>
                                    </tr>';
                            } ?>
                            </tbody>
                          </table>
                        </div>

                        <div id="log" class="tab-pane fade" style="overflow-x: hidden; overflow-y: scroll; max-height:300px;">
                          <ol reversed><?php echo $this->contact->log;?></ol>
                        </div>

                        <div id="dropdown1" class="tab-pane fade">
                            <form class="form-horizontal" action="<?php echo Config::get('URL') . 'employee/uploadImage/' . $this->contact->user_name; ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Nama Photo/Scan</label>
                                  <div class="col-sm-10">
                                    <input type="text" name="image_name" class="form-control" placeholder="Nama file photo atau hasil scan yang diupload">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Pilih Photo/Scan</label>
                                  <div class="col-sm-10">
                                    <input type="file" class="form-control" name="file_name" required />
                                    <span id="helpBlock" class="help-block">Hanya file image tipe jpg, jpeg dan png yang diijinkan, ukuran maksimum 3MB dan minimum dimensi 100 x 100 pixel</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Keterangan</label>
                                  <div class="col-sm-10">
                                    <textarea name="note" class="form-control" rows="3"></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-info">Upload Photo</button>
                                  </div>
                                </div>
                            </form>
                        </div>

                        <div id="dropdown2" class="tab-pane fade">
                          <form class="form-horizontal" action="<?php echo Config::get('URL') . 'employee/uploadDocument/' . $this->contact->user_name; ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Nama Document</label>
                                  <div class="col-sm-10">
                                    <input type="text" name="document_name" class="form-control" placeholder="Nama file photo atau hasil scan yang diupload">
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Pilih Document</label>
                                  <div class="col-sm-10">
                                    <input type="file" class="form-control" name="file_name" required />
                                    <span id="helpBlock" class="help-block">Hanya file tipe dokumen yang diijinkan, seperti: PDF, Microsoft Word (doc, docx), Mircosoft Excel (xls, xlsx) dan Microsoft PowerPoint (ppt, pptx). Ukuran maksimum 3MB</span>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-2 control-label">Keterangan</label>
                                  <div class="col-sm-10">
                                    <textarea name="note" class="form-control" rows="3"></textarea>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-info">Upload Document</button>
                                  </div>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div><!-- /.tabbable-->
          </div>
        </div><!-- ./row -->

        <!-- PAGE CONTENT ENDS -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.page-content -->
</div><!-- /.main-content-inner -->
</div><!-- /.main-content -->