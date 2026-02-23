<!--
    How to use? Set variable in controller, include in views and give a javascript logic

    1. Set in the $data variable, set value with TRUE or FALSE, or delete if not use
    // Init combo list
    $cxfilter__list_static = '<option value="all">--Semua--</option>';
    $cxfilter__jenis_barang_list = $this->init_list($this->KelompokbarangModel->getAll(), 'nama', 'nama', 'all', $cxfilter__list_static);
    $cxfilter__supplier_list = $this->init_list($this->SupplierModel->getAll(), 'id', 'nama_supplier', 'all', $cxfilter__list_static);
    // Set payload
    'cx_filter' => array(
        'cxfilter__tanggal' => true,
        'cxfilter__no_po' => true,
        'cxfilter__tanggal_po' => true,
        'cxfilter__no_faktur' => true,
        'cxfilter__tanggal_faktur' => true,
        'cxfilter__no_mutasi' => true,
        'cxfilter__tanggal_mutasi' => true,
        'cxfilter__tanggal_terima' => true,
        'cxfilter__supplier' => true,
        'cxfilter__cara_bayar' => true,
        'cxfilter__jenis_barang' => true,
        'cxfilter__tujuan' => true,
        'cxfilter__sumber' => true,
        'cxfilter__submit_filter' => true,
        'cxfilter__submit_xlsx' => true,
        'cxfilter__submit_simple_xlsx' => true,
        'cxfilter__supplier_list' => $cxfilter__supplier_list,
        'cxfilter__jenis_barang_list' => $cxfilter__jenis_barang_list,
    )
    2. Include this file with php function : include_once(APPPATH . 'modules/_component/filter.report.grid.php')
    3. Create function and give your logic in javascript module file
        - handleCxFilter_submit();
        - handleCxFilter_xlsx();
        - handleCxFilter_xlsxSimple();
        To retrieve parameters according to form fields, use function handleCxFilter_getParams() this will return the query param as string
        To set visibilty export button, use function handleCxFilter_setXlsx("dataTableId"). Place in drawCallback() dataTables event
-->

<div class="card card-icon card-collapsable">
    <div class="row no-gutters">
        <?php if (!$is_mobile) : ?>
            <div class="col-auto card-icon-aside bg-primary">
                <i data-feather="filter" class="text-white"></i>
            </div>
        <?php endif ?>
        <div class="col">
            <div id="collapseCardCxFilter">
                <div class="card-body pb-3">
                    <div class="row">
                        <?php if (isset($cx_filter['cxfilter__tanggal']) && $cx_filter['cxfilter__tanggal'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Tanggal</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[tanggal]" class="form-control cx__filter-textfield flatpickr-date-range bg-white" data-prefix="cx__filter-tanggal" placeholder="--Pilih--" />
                                        <!-- Temp field -->
                                        <input type="hidden" name="cx_filter[tanggal_start]" class="cx__filter-tanggal_start" readonly />
                                        <input type="hidden" name="cx_filter[tanggal_end]" class="cx__filter-tanggal_end" readonly />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__no_po']) && $cx_filter['cxfilter__no_po'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">No. PO</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[no_po]" class="form-control cx__filter-textfield" placeholder="Nomor PO" />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if (isset($cx_filter['cxfilter__tanggal_po']) && $cx_filter['cxfilter__tanggal_po'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Tgl. PO</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[tanggal_po]" class="form-control cx__filter-textfield flatpickr-date-range bg-white" data-prefix="cx__filter-tanggal_po" placeholder="--Pilih--" />
                                        <!-- Temp field -->
                                        <input type="hidden" name="cx_filter[tanggal_po_start]" class="cx__filter-tanggal_po_start" readonly />
                                        <input type="hidden" name="cx_filter[tanggal_po_end]" class="cx__filter-tanggal_po_end" readonly />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__no_faktur']) && $cx_filter['cxfilter__no_faktur'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">No. Faktur</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[no_faktur]" class="form-control cx__filter-textfield" placeholder="Nomor Faktur" />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if (isset($cx_filter['cxfilter__tanggal_faktur']) && $cx_filter['cxfilter__tanggal_faktur'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Tgl. Faktur</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[tanggal_faktur]" class="form-control cx__filter-textfield flatpickr-date-range bg-white" data-prefix="cx__filter-tanggal_faktur" placeholder="--Pilih--" />
                                        <!-- Temp field -->
                                        <input type="hidden" name="cx_filter[tanggal_faktur_start]" class="cx__filter-tanggal_faktur_start" readonly />
                                        <input type="hidden" name="cx_filter[tanggal_faktur_end]" class="cx__filter-tanggal_faktur_end" readonly />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__no_mutasi']) && $cx_filter['cxfilter__no_mutasi'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">No. Mutasi</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[no_mutasi]" class="form-control cx__filter-textfield" placeholder="Nomor Mutasi" />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <?php if (isset($cx_filter['cxfilter__tanggal_mutasi']) && $cx_filter['cxfilter__tanggal_mutasi'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Tgl. Mutasi</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[tanggal_mutasi]" class="form-control cx__filter-textfield flatpickr-date-range bg-white" data-prefix="cx__filter-tanggal_mutasi" placeholder="--Pilih--" />
                                        <!-- Temp field -->
                                        <input type="hidden" name="cx_filter[tanggal_mutasi_start]" class="cx__filter-tanggal_mutasi_start" readonly />
                                        <input type="hidden" name="cx_filter[tanggal_mutasi_end]" class="cx__filter-tanggal_mutasi_end" readonly />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__tanggal_terima']) && $cx_filter['cxfilter__tanggal_terima'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Tgl. Terima</label>
                                    <div class="col-xs-12 col-md-9">
                                        <input type="text" name="cx_filter[tanggal_terima]" class="form-control cx__filter-textfield flatpickr-date-range bg-white" data-prefix="cx__filter-tanggal_terima" placeholder="--Pilih--" />
                                        <!-- Temp field -->
                                        <input type="hidden" name="cx_filter[tanggal_terima_start]" class="cx__filter-tanggal_terima_start" readonly />
                                        <input type="hidden" name="cx_filter[tanggal_terima_end]" class="cx__filter-tanggal_terima_end" readonly />
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__supplier']) && $cx_filter['cxfilter__supplier'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Supplier</label>
                                    <div class="col-xs-12 col-md-9">
                                        <div class="cx__filter-combo-wrapper">
                                            <select name="cx_filter[supplier]" class="form-control cx__filter-combo select2">
                                                <?= (isset($cx_filter['cxfilter__supplier_list'])) ? $cx_filter['cxfilter__supplier_list'] : '<option value="all">--Semua--</option>' ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__cara_bayar']) && $cx_filter['cxfilter__cara_bayar'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Cara Bayar</label>
                                    <div class="col-xs-12 col-md-9">
                                        <div class="cx__filter-combo-wrapper">
                                            <select name="cx_filter[cara_bayar]" class="form-control cx__filter-combo select2">
                                                <option value="all" selected>--Semua--</option>
                                                <option value="kredit">Kredit</option>
                                                <option value="tunai">Tunai</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__jenis_barang']) && $cx_filter['cxfilter__jenis_barang'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Jns. Barang</label>
                                    <div class="col-xs-12 col-md-9">
                                        <div class="cx__filter-combo-wrapper">
                                            <select name="cx_filter[jenis_barang]" class="form-control cx__filter-combo select2">
                                                <?= (isset($cx_filter['cxfilter__jenis_barang_list'])) ? $cx_filter['cxfilter__jenis_barang_list'] : '<option value="all">--Semua--</option>' ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__tujuan']) && $cx_filter['cxfilter__tujuan'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Tujuan</label>
                                    <div class="col-xs-12 col-md-9">
                                        <div class="cx__filter-combo-wrapper">
                                            <select name="cx_filter[tujuan]" class="form-control cx__filter-combo select2"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>

                        <?php if (isset($cx_filter['cxfilter__sumber']) && $cx_filter['cxfilter__sumber'] === true) : ?>
                            <div class="col-xs-12 col-md-6">
                                <div class="form-group row mb-2">
                                    <label class="control-label col-xs-12 col-md-2" style="width: 110px;">Sumber</label>
                                    <div class="col-xs-12 col-md-9">
                                        <div class="cx__filter-combo-wrapper">
                                            <select name="cx_filter[sumber]" class="form-control cx__filter-combo select2">
                                                <option value="all" selected>--Semua--</option>
                                                <option value="<?= md5('Pembelian Persediaan') ?>">Pembelian Persediaan</option>
                                                <option value="<?= md5('Pembelian Non Persediaan') ?>">Pembelian Non Persediaan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="form-group row mb-2 mt-2">
                        <?php if (!$is_mobile) : ?>
                            <label class="control-label col-xs-12 col-md-2" style="width: 110px;">&nbsp;</label>
                        <?php endif ?>
                        <div class="col-xs-12 col-md-4">
                            <?php if (isset($cx_filter['cxfilter__submit_filter']) && $cx_filter['cxfilter__submit_filter'] === true) : ?>
                                <button class="btn btn--raised btn-primary btn--icon-text rounded cx__filter-submit page-action-filter" onclick="handleCxFilter_submit()">
                                    <i class="zmdi zmdi-filter-list"></i> Filter
                                </button>
                            <?php endif ?>
                            <?php if (isset($cx_filter['cxfilter__submit_xlsx']) && $cx_filter['cxfilter__submit_xlsx'] === true) : ?>
                                <button class="btn btn--raised btn-warning btn--icon-text rounded cx__filter-submit page-action-xlsx" onclick="handleCxFilter_xlsx()" style="display: none;">
                                    <i class="zmdi zmdi-download"></i> Unduh Excel
                                </button>
                            <?php endif ?>
                            <?php if (isset($cx_filter['cxfilter__submit_simple_xlsx']) && $cx_filter['cxfilter__submit_simple_xlsx'] === true) : ?>
                                <button class="btn btn--raised btn-warning btn--icon-text rounded cx__filter-submit page-action-xlsx" onclick="handleCxFilter_xlsxSimple()" style="display: none;">
                                    <i class="zmdi zmdi-download"></i> Unduh Excel (Simpel)
                                </button>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>