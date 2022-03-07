      <!-- Main Search -->
      <div class="row row-backbordered home-search">
        <div class="col-sm-12">
          <div class="panel panel-default panel-floating panel-floating-inline">
            <div class="panel-body">
              <form action="<?php echo Config::get('URL'); ?>/search" method="get">
                <div class="input-group">
                  <input type="text" class="form-control" name="term"  placeholder="Ketik di sini jasa yang dicari diikuti nama kota. Contoh: Tukang Kompor Malang">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button">Cari Jasa</button>
                  </span>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- /Main Search -->
      
        <?php $this->renderFeedbackMessages(); ?>


      <!-- Penyedia Jasa Terbaru -->
      <div class="row row-padded hero-theme">
        <div class="col-sm-12">
           <h1 class="hero-header text-center row-bordered">Hasil Pencarian</h1>
          <table class="table table-striped table-hover">
          <?php if ($this->result) { ?>
            <tbody>
              <?php foreach ($this->result as $post) { ?>

              <tr>
              <td>
                   <strong><a href="<?= Config::get('URL') . 'halaman/baca/' . $post->slug; ?>"><?= $post->title; ?></a></strong>
                   <br>
                  <?= substr(strip_tags($post->content), 0, 300) . '....'; ?>
                </td>
              </tr>


              <?php } //end foreach?>
          <?php } else {echo '<tbody>';}// end if
            // end of result from database and start result from google and bing search engine
            if ($this->se_results) {
          ?>

          
              <?php 
              foreach ($this->se_results as $key => $value) { ?>
              <tr>
                <td>
                  <strong>
                    <a href="<?= Config::get('URL') . 'halaman/bacaan/' . $value['url'] ?>">
                      <?= $value['title'] ?>
                    </a>
                  </strong>
                  <br>
                    <?= $value['keterangan'] ?>
                </td>
              </tr>
              <?php } // end foreach?>
           
          <?php } // end if ?>
                
           </tbody>
          </table>
        </div>
      </div>
      <!-- /Penyedia Jasa Terbaru -->

      <!-- Explore -->
      <div class="row row-backbordered">
        <div class="col-sm-12">
          <div class="panel panel-default panel-floating panel-floating-inline">
            <div class="panel-body">
                <div class="panel-content">
            <h5><strong>Bingung cara mencari Jasa Panggilan yang benar?</strong></h5>
            <p class="text-muted"><small>Klik link/tautan cara mencari jasa panggilan yang baik dan benar di samping.</small></p>
          </div>
                <div class="panel-actions">
                    <a class="btn btn-lg pull-right" href="http://jasapanggilan.com/halaman/baca/cara-memasukkan-kata-kunci-yang-baik">Bantuan Pencarian</a>
                </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Explore -->