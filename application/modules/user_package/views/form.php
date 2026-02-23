<div class="modal fade" id="modal-form-user_package" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">
                    <?= (isset($card_title)) ? $card_title : 'Form Paket Pengguna' ?>
                </h5>
            </div>
            <div class="spinner">
                <div class="lds-hourglass"></div>
            </div>
            <div class="modal-body">
                <form id="form-user_package" autocomplete="off">
                    <!-- CSRF -->
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" />

                    <div class="form-group">
                        <label required>Pengguna</label>
                        <div class="select">
                            <select name="user_id" class="form-control select2 user_package-user_id" data-placeholder="Pilih Pengguna" required>
                                <option value=""></option>
                                <?= $list_user ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label required>Paket</label>
                        <div class="select">
                            <select name="package_id" class="form-control select2 user_package-package_id" data-placeholder="Pilih Paket" required>
                                <option value=""></option>
                                <?= $list_package ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Tanggal Mulai</label>
                                <input type="text" name="start_date" class="form-control flatpickr-date user_package-start_date" placeholder="YYYY-MM-DD" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Tanggal Akhir</label>
                                <input type="text" name="end_date" class="form-control flatpickr-date user_package-end_date" placeholder="YYYY-MM-DD" required />
                                <i class="form-group__bar"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Status</label>
                                <div class="select">
                                    <select name="status" class="form-control user_package-status" required>
                                        <option value="active">Active</option>
                                        <option value="expired">Expired</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label required>Status Pembayaran</label>
                                <div class="select">
                                    <select name="payment_status" class="form-control user_package-payment_status" required>
                                        <option value="pending">Pending</option>
                                        <option value="paid">Paid</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <small class="form-text text-muted">
                        Fields with red stars (<label required></label>) are required.
                    </small>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn--icon-text user_package-action-save">
                    <i class="zmdi zmdi-save"></i> Simpan
                </button>
                <button type="button" class="btn btn-light btn--icon-text" data-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
