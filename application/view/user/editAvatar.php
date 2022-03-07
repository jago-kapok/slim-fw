<div class="main-content">
    <div class="page-content">
        <div class="row">
            <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <h1>Ganti Photo Profile</h1>

                <!-- echo out the system feedback (error and success messages) -->
                <?php $this->renderFeedbackMessages(); ?>

                <div class="box">
                    <form action="<?php echo Config::get('URL') . 'user/updateAvatar/' . $this->user_id; ?>" method="post" enctype="multipart/form-data">
                        <label for="avatar_file">Pilih foto yang akan diupload</label>
                        <input type="file" name="avatar_file" required />
                        <!-- max size 5 MB (as many people directly upload high res pictures from their digital cameras) -->
                        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                        <br>
                        <input type="submit" value="Upload" class="btn btn-info" />
                    </form>
                </div>

                <hr>

                <div class="box">
                    <h3>Delete Photo Profile</h3>
                    <p>Click link berikut untuk delete photo profile: <a href="<?php echo Config::get('URL'); ?>user/deleteAvatar_action" class="btn btn-sm btn-danger">Delete Photo</a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>