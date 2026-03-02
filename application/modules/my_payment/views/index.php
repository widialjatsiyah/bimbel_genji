<style>
	#snap-container {
  width: 800px;      /* Lebih lebar dari default */
  height: 700px;     /* Lebih tinggi dari default */
  margin: 0 auto;    /* Tengah */
  border: 1px solid #ddd;
  border-radius: 8px;
}
</style>
<section id="my-payment">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>

            <div class="table-responsive">
                <table id="table-my-payment" class="table table-bordered">
                    <thead class="thead-default">
                        <tr>
                            <th width="50">No</th>
                            <th>Order ID</th>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Harga</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
	<!-- Tambahkan div sebagai container Snap -->
<div id="snap-container" style="width: 600px; height: 700px;"></div>
</section>
