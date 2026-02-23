<div class="modal fade" id="modal-form-example" data-backdrop="static" data-keyboard="false">
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
        <form id="form-example" autocomplete="off">
          <!-- CSRF -->
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

          <div class="form-group">
            <label required>Text</label>
            <input type="text" name="input_text" class="form-control example-input_text" maxlength="255" placeholder="Text" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Number</label>
            <input type="number" name="input_number" class="form-control example-input_number" min="0" max="100" placeholder="Number" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Money</label>
            <input type="text" name="input_money" class="form-control mask-money example-input_money" placeholder="Money" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Date</label>
            <input type="text" name="input_date" class="form-control flatpickr-date example-input_date" placeholder="Date" required />
            <i class="form-group__bar"></i>
          </div>
          <div class="form-group">
            <label required>Combobox</label>
            <div class="select">
              <select name="input_combobox" class="form-control select2 example-input_combobox" data-placeholder="Select &#8595;" required>
                <?= $list_combobox ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label required>Textarea</label>
            <textarea name="input_textarea" class="form-control example-input_textarea" rows="3" placeholder="Textarea" required></textarea>
            <i class="form-group__bar"></i>
          </div>

          <small class="form-text text-muted">
            Fields with red stars (<label required></label>) are required.
          </small>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success btn--icon-text example-action-save">
          <i class="zmdi zmdi-save"></i> Simpan
        </button>
        <button type="button" class="btn btn-light btn--icon-text example-action-cancel" data-dismiss="modal">
          Batal
        </button>
      </div>
    </div>
  </div>
</div>