<!-- =========================================================
     MODAL MANAGE SOAL TRY OUT
========================================================= -->

<div class="modal fade" id="modal-manage-questions" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl" role="document">

		<div class="modal-content">

			<!-- ================= HEADER ================= -->
			<div class="modal-header manage-question-header">

				<div>
					<h4 class="modal-title mb-1">
						Kelola Soal Try Out
					</h4>

					<div class="manage-question-session">
						<span class="text-muted">
							Sesi:
						</span>

						<strong id="session_name_label">
							-
						</strong>
					</div>
				</div>

				<button
					type="button"
					class=" btn  btn-sm btn-outline-secondary"
					data-dismiss="modal"
					aria-label="Close">

					<span aria-hidden="true">&times;</span>

				</button>

			</div>


			<!-- ================= BODY ================= -->
			<div class="modal-body manage-question-body">

				<div class="row">

					<!-- =================================================
                         LEFT : FORM TAMBAH SOAL
                    ================================================== -->
					<div class="col-lg-4">

						<div class="question-panel">

							<div class="question-panel-header">

								<div class="question-panel-icon">
									<i class="zmdi zmdi-plus"></i>
								</div>

								<div>
									<h5>
										Tambah Soal
									</h5>

									<p>
										Pilih soal dan tentukan metode
										penomoran.
									</p>
								</div>

							</div>


							<div class="question-panel-body">

								<!-- ================= SOAL ================= -->
								<div class="form-group">

									<label class="form-label" required>
										Pilihan Soal
									</label>
									<input type="hidden" name="current_session_id" id="current_session_id" >
									<select
										name="question_ids[]"
										id="question_select"
										class="form-control select2-multiple"
										multiple="multiple"
										data-placeholder="Pilih soal yang akan ditambahkan">

										<!-- Options AJAX -->

									</select>

									<div class="field-help">
										<i class="zmdi zmdi-info-outline"></i>

										Anda dapat memilih lebih dari satu
										soal sekaligus.
									</div>

								</div>


								<!-- ================= METODE ================= -->
								<div class="form-group">

									<label class="form-label" required>
										Metode Penomoran
									</label>


									<!-- Sequential -->
									<label class="ordering-option">

										<input
											type="radio"
											name="ordering_method"
											value="sequential"
											checked>

										<span class="ordering-content">

											<span class="ordering-title">
												Urutkan Berurutan
											</span>

											<span class="ordering-description">
												Soal dimulai dari nomor yang
												ditentukan kemudian bertambah
												secara berurutan.
											</span>

										</span>

									</label>


									<!-- Auto -->
									<label class="ordering-option">

										<input
											type="radio"
											name="ordering_method"
											value="auto">

										<span class="ordering-content">

											<span class="ordering-title">
												Nomor Otomatis
											</span>

											<span class="ordering-description">
												Melanjutkan nomor terakhir
												yang tersedia pada sesi.
											</span>

										</span>

									</label>

								</div>


								<!-- ================= NOMOR AWAL ================= -->
								<div
									id="start-order-input"
									class="start-order-box">

									<label
										for="start_order"
										class="form-label">

										Nomor Urut Awal
									</label>

									<input
										type="number"
										name="start_order"
										id="start_order"
										class="form-control"
										min="1"
										value="1">

									<div class="field-help">
										Nomor pertama yang digunakan
										untuk soal baru.
									</div>

								</div>

								<div class="form-group mt-4 text-center">
									<button
										type="button"
										class="btn btn-success btn-block btn-save-question manage-questions-action-save">

										<i class="zmdi zmdi-save mr-1"></i>

										Simpan & Tambahkan Soal

									</button>
								</div>
							</div>



							<!-- ================= ACTION ================= -->
							<div class="question-panel-footer">



							</div>

						</div>

					</div>


					<!-- =================================================
                         RIGHT : DAFTAR SOAL
                    ================================================== -->
					<div class="col-lg-8">

						<div class="question-list-panel">

							<!-- Header -->
							<div class="question-list-header">

								<div>

									<h5>
										Daftar Soal
									</h5>

									<p>
										Soal yang saat ini terdaftar
										dalam sesi try out.
									</p>

								</div>

								<div class="question-list-info">

									<span class="question-count">
										<i class="zmdi zmdi-format-list-numbered"></i>
										Soal dalam sesi
									</span>

								</div>

							</div>


							<!-- Table -->
							<div class="question-table-wrapper">

								<div class="table-responsive">

									<table
										id="table-session-questions"
										class="table table-hover question-table">

										<thead>

											<tr>

												<th
													width="60"
													class="text-center">

													No

												</th>

												<th>

													Isi Soal

												</th>

												<th
													width="100"
													class="text-center">

													Nilai

												</th>

												<th
													width="130"
													class="text-center">

													Waktu

												</th>

												<th
													width="90"
													class="text-center">

													Aksi

												</th>

											</tr>

										</thead>

										<tbody>

											<!-- DataTables -->

										</tbody>

									</table>

								</div>

							</div>

						</div>

					</div>

				</div>


				<!-- ================= FOOT NOTE ================= -->
				<div class="required-info">

					<i class="zmdi zmdi-info-outline"></i>

					<span>
						Field dengan tanda
						<label required></label>
						wajib diisi.
					</span>

				</div>

			</div>


			<!-- ================= FOOTER ================= -->
			<div class="modal-footer manage-question-footer">

				<button
					type="button"
					class="btn btn-light"
					data-dismiss="modal">

					<i class="zmdi zmdi-close mr-1"></i>
					Tutup

				</button>

			</div>

		</div>

	</div>
</div>


<!-- =========================================================
     STYLE
========================================================= -->

<style>
	/* =========================================================
       MODAL
    ========================================================= */

	#manageQuestionsModal .modal-dialog {
		max-width: 1200px;
		width: calc(100% - 30px);
	}

	#manageQuestionsModal .modal-content {
		border: 0;
		border-radius: 8px;
		overflow: hidden;
		box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
	}


	/* =========================================================
       HEADER
    ========================================================= */

	.manage-question-header {
		display: flex;
		align-items: center;
		justify-content: space-between;

		padding: 18px 24px;

		background: #fff;
		border-bottom: 1px solid #e9ecef;
	}

	.manage-question-header .modal-title {
		font-size: 19px;
		font-weight: 600;
		color: #333;
	}

	.manage-question-header .modal-title i {
		color: #4caf50;
	}

	.manage-question-session {
		font-size: 13px;
		margin-top: 3px;
	}

	.manage-question-session strong {
		color: #333;
		margin-left: 4px;
	}


	/* =========================================================
       BODY
    ========================================================= */

	.manage-question-body {
		background: #f7f8fa;
		padding: 22px;
	}

	.manage-question-body>.row {
		margin-left: -8px;
		margin-right: -8px;
	}

	.manage-question-body>.row>[class*="col-"] {
		padding-left: 8px;
		padding-right: 8px;
	}


	/* =========================================================
       LEFT PANEL
    ========================================================= */

	.question-panel,
	.question-list-panel {
		background: #fff;
		border: 1px solid #e4e7eb;
		border-radius: 7px;
		height: 100%;
	}

	.question-panel {
		display: flex;
		flex-direction: column;
	}

	.question-panel-header {
		display: flex;
		align-items: center;

		padding: 18px;

		border-bottom: 1px solid #edf0f2;
	}

	.question-panel-icon {
		width: 38px;
		height: 38px;

		display: flex;
		align-items: center;
		justify-content: center;

		margin-right: 11px;

		border-radius: 6px;

		background: #eaf7ed;
		color: #43a047;

		font-size: 18px;
	}

	.question-panel-header h5,
	.question-list-header h5 {
		margin: 0;

		font-size: 15px;
		font-weight: 600;

		color: #30343b;
	}

	.question-panel-header p,
	.question-list-header p {
		margin: 3px 0 0;

		font-size: 12px;
		color: #89919a;
	}


	.question-panel-body {
		padding: 18px;
		flex: 1;
	}


	/* =========================================================
       FORM
    ========================================================= */

	.form-label {
		display: block;

		margin-bottom: 8px;

		font-size: 13px;
		font-weight: 600;

		color: #41464d;
	}

	.form-group {
		margin-bottom: 20px;
	}

	.form-group:last-child {
		margin-bottom: 0;
	}

	.field-help {
		display: flex;
		align-items: flex-start;

		margin-top: 7px;

		font-size: 11px;
		line-height: 1.5;

		color: #9299a1;
	}

	.field-help i {
		margin-right: 5px;
		margin-top: 1px;
	}


	/* =========================================================
       ORDERING OPTION
    ========================================================= */

	.ordering-option {
		position: relative;

		display: flex;
		align-items: flex-start;

		padding: 11px 12px;

		margin-bottom: 8px;

		border: 1px solid #e2e5e8;
		border-radius: 6px;

		cursor: pointer;

		transition: all .15s ease;

		background: #fff;
	}

	.ordering-option:hover {
		border-color: #b8c0c8;
		background: #fafbfc;
	}

	.ordering-option input {
		margin-top: 3px;
		margin-right: 9px;
	}

	.ordering-content {
		display: flex;
		flex-direction: column;
	}

	.ordering-title {
		font-size: 12px;
		font-weight: 600;
		color: #3d434a;
	}

	.ordering-description {
		margin-top: 3px;

		font-size: 11px;
		line-height: 1.4;

		color: #9299a1;
	}


	/* =========================================================
       START ORDER
    ========================================================= */

	.start-order-box {
		padding: 13px;

		background: #f8f9fa;

		border: 1px solid #e5e8eb;
		border-radius: 6px;
	}

	.start-order-box .form-control {
		background: #fff;
	}


	/* =========================================================
       SAVE BUTTON
    ========================================================= */

	.question-panel-footer {
		padding: 15px 18px;

		border-top: 1px solid #edf0f2;
	}

	.btn-save-question {
		height: 40px;

		font-size: 13px;
		font-weight: 500;

		border-radius: 5px;
	}


	/* =========================================================
       RIGHT PANEL
    ========================================================= */

	.question-list-panel {
		display: flex;
		flex-direction: column;

		min-height: 500px;
	}

	.question-list-header {
		display: flex;
		align-items: center;
		justify-content: space-between;

		padding: 18px;

		border-bottom: 1px solid #edf0f2;
	}

	.question-count {
		display: inline-flex;
		align-items: center;

		padding: 6px 10px;

		background: #f3f5f7;

		border-radius: 4px;

		font-size: 11px;
		color: #69717a;
	}

	.question-count i {
		margin-right: 5px;
	}


	/* =========================================================
       TABLE
    ========================================================= */

	.question-table-wrapper {
		flex: 1;
		padding: 0 10px 10px;
	}

	.question-table {
		margin: 0;

		font-size: 12px;
	}

	.question-table thead th {
		padding: 11px 8px;

		background: #fafbfc;

		border-top: 0;
		border-bottom: 1px solid #e5e8eb;

		font-size: 11px;
		font-weight: 600;

		color: #737b84;

		white-space: nowrap;
	}

	.question-table tbody td {
		padding: 10px 8px;

		vertical-align: middle;

		border-top: 1px solid #f0f1f3;

		color: #454b52;
	}

	.question-table tbody tr:hover {
		background: #fafcfa;
	}


	/* =========================================================
       REQUIRED INFO
    ========================================================= */

	.required-info {
		display: flex;
		align-items: center;

		margin-top: 15px;
		padding: 9px 12px;

		background: #fff;

		border: 1px solid #e6e9ec;
		border-radius: 5px;

		font-size: 11px;

		color: #858c94;
	}

	.required-info i {
		margin-right: 6px;
	}


	/* =========================================================
       FOOTER
    ========================================================= */

	.manage-question-footer {
		padding: 12px 22px;

		background: #fff;

		border-top: 1px solid #e9ecef;
	}

	.manage-question-footer .btn {
		min-width: 90px;
	}


	/* =========================================================
       SELECT2
    ========================================================= */

	#manageQuestionsModal .select2-container {
		width: 100% !important;
	}

	#manageQuestionsModal .select2-selection--multiple {
		min-height: 42px;

		border: 1px solid #ddd;
		border-radius: 4px;
	}


	/* =========================================================
       RESPONSIVE
    ========================================================= */

	@media (max-width: 991px) {

		.question-panel {
			margin-bottom: 15px;
		}

		.question-list-panel {
			min-height: 400px;
		}

	}
</style>

