<div class="main-content">
                <!-- /section:basics/content.breadcrumbs -->
                <div class="page-content">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- PAGE CONTENT BEGINS -->

        <!-- echo out the system feedback (error and success messages) -->
        <?php $this->renderFeedbackMessages(); ?>
                <table class="table">
                    <tr class="<?= ($this->user->is_active == 0 ? 'inactive' : 'active'); ?>">
                        <td>Id</td>
                        <td><?= $this->user->uid; ?></td>
                        <td>Photo</td>
                        <td class="avatar">
                            <?php if (isset($this->user->user_avatar_link)) { ?>
                                <img src="<?= $this->user->user_avatar_link; ?>" />
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><?= $this->user->user_name; ?></td>
                        <td>Nama</td>
                        <td><?= $this->user->full_name; ?></td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td><?= $this->user->email; ?></td>
                        <td>Telpon</td>
                        <td><?= $this->user->phone; ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">Alamat Asal/Sesuai KTP</td>
                    </tr>
                    <tr>
                        <td>Jalan</td>
                        <td colspan="3"><?= $this->user->address_street; ?></td>
                    </tr>
                    <tr>
                        <td>Kota</td>
                        <td><?= $this->user->address_city; ?></td>
                        <td>Propinsi</td>
                        <td><?= $this->user->address_state; ?></td>
                    </tr>
            </table>

