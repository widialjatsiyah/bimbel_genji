<div class="modal fade" id="modal-form-layanan" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title pull-left">
          <?= (isset($card_title)) ? $card_title : 'Form' ?>
        </h5>
      </div>
      <div class="spinner">
        <div class="lds-hourglass"></div>
      </div>
      <div class="modal-body">
        <form id="form-layanan" enctype="multipart/form-data" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Nama Layanan</label>
            <input type="text" name="nama_layanan" class="form-control layanan-nama_layanan" maxlength="100" placeholder="Nama Layanan" required />
            <i class="form-group__bar"></i>
          </div>
          
          <div class="form-group">
            <label>Keterangan</label>
            <textarea name="keterangan" class="form-control layanan-keterangan" rows="3" placeholder="Keterangan"></textarea>
            <i class="form-group__bar"></i>
          </div>
          
          <div class="form-group">
            <label>File Gambar</label>
            <input type="file" name="file_imgae" class="form-control layanan-file_imgae" accept="image/jpg,image/jpeg,image/png,image/gif" />
            <i class="form-group__bar"></i>
            
            <!-- Preview for existing image -->
            <div id="image-preview-container" style="margin-top: 10px; display: none;">
              <label>Gambar Saat Ini:</label>
              <div>
                <img id="current-image-preview" src="" alt="Current Image" style="max-width: 200px; max-height: 200px;" />
              </div>
            </div>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text layanan-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text layanan-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>