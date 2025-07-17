<div class="container-fluid">
    <div class="row">
        <div class="col-md2"></div>
        <div class="col-md8">
            <div class="btn btn-sm btn-success">
                <?php
                $grand_total = 0;
                if ($keranjang = $this->cart->contents()) {
                    foreach ($keranjang as $item) {
                        $grand_total =  $grand_total + $item['subtotal'];
                    }
                    echo "<h5>Total Belanja Anda: Rp. " . number_format($grand_total, 0, ',', '.') . "</h5>";
                } else {
                    echo "<h4>Keranjang Belanja Anda Masih Kosong</h4>"; // Perbaiki tag penutup h4
                }
                ?>
            </div> <br><br>

            <h3>Input Alamat Pengiriman dan Pembayaran</h3>

            <form method="post" action="<?php echo base_url('dashboard/proses_pesanan') ?> ">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Nama Lengkap Anda" class="form-control" value="<?php echo set_value('nama'); ?>">
                    <?php echo form_error('nama', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input type="text" name="alamat" id="autocomplete_alamat" placeholder="Ketik alamat Anda di sini" class="form-control" value="<?php echo set_value('alamat'); ?>">
                    <?php echo form_error('alamat', '<div class="text-danger small mt-1">', '</div>'); ?>
                    <input type="hidden" name="latitude_pengiriman" id="latitude_pengiriman" value="<?php echo set_value('latitude_pengiriman'); ?>">
                    <input type="hidden" name="longitude_pengiriman" id="longitude_pengiriman" value="<?php echo set_value('longitude_pengiriman'); ?>">
                </div>

                <div class="form-group">
                    <label>Verifikasi Lokasi Pengiriman</label>
                    <div id="map" style="height: 300px; width: 100%; border: 1px solid #ddd;"></div>
                    <small class="form-text text-muted">Seret pin merah untuk lokasi yang lebih akurat.</small>
                </div>
                <div class="form-group">
                    <label>No. Telepon</label>
                    <input type="text" name="no_telp" placeholder="Nomor Telepon Anda" class="form-control" value="<?php echo set_value('no_telp'); ?>">
                    <?php echo form_error('no_telp', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label>Jasa Pengiriman</label>
                    <select class="form-control" name="jasa_pengiriman" id="jasa_pengiriman_select">
                        <option value="">-- Pilih Jasa Pengiriman --</option>
                        <option value="JNE" <?php echo set_select('jasa_pengiriman', 'JNE'); ?>>JNE</option>
                        <option value="TIKI" <?php echo set_select('jasa_pengiriman', 'TIKI'); ?>>TIKI</option>
                        <option value="JNT" <?php echo set_select('jasa_pengiriman', 'JNT'); ?>>Pos Indonesia</option>
                        <option value="Gojek" <?php echo set_select('jasa_pengiriman', 'Gojek'); ?>>Gojek</option>
                        <option value="Grab" <?php echo set_select('jasa_pengiriman', 'Grab'); ?>>Grab</option>
                    </select>
                    <?php echo form_error('jasa_pengiriman', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label>Ongkos Kirim:</label>
                    <h5 id="ongkos_kirim_display" class="font-weight-bold text-info">Rp. 0</h5>
                    <input type="hidden" name="ongkos_kirim" id="ongkos_kirim_hidden" value="0">
                </div>
                <div class="form-group">
                    <label>Pilih Bank</label>
                    <select class="form-control" name="metode_pembayaran">
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="BRI - 4512-0102-7163-533" <?php echo set_select('metode_pembayaran', 'BRI - 4512-0102-7163-533'); ?>>BRI - 4512-0102-7163-533</option>
                        <option value="Bank Kalsel - 6271 1000 0288 3970" <?php echo set_select('metode_pembayaran', 'Bank Kalsel - 6271 1000 0288 3970'); ?>>Bank Kalsel - 6271 1000 0288 3970</option>
                        <option value="Shopee Pay - 081257388785" <?php echo set_select('metode_pembayaran', 'Shopee Pay - 081257388785'); ?>>Shopee Pay - 081257388785</option>
                        <option value="Bank Jago - 506497183618" <?php echo set_select('metode_pembayaran', 'Bank Jago - 506497183618'); ?>>Bank Jago - 506497183618</option>
                        <option value="Sea Bank - 9017-9485-6963" <?php echo set_select('metode_pembayaran', 'Sea Bank - 9017-9485-6963'); ?>>Sea Bank - 9017-9485-6963</option>
                    </select>
                    <?php echo form_error('metode_pembayaran', '<div class="text-danger small mt-1">', '</div>'); ?>
                </div>

                <div class="form-group">
                    <label>Kode Voucher (Opsional)</label>
                    <input type="text" name="kode_voucher" id="kode_voucher" placeholder="Masukkan kode voucher Anda" class="form-control">
                    <button type="button" id="btn_terapkan_voucher" class="btn btn-info btn-sm mt-2">Terapkan Voucher</button>
                    <p id="pesan_voucher" class="mt-2"></p>
                </div>
                
                <div class="form-group">
                    <label>Total Belanja (setelah diskon):</label>
                    <h4 id="final_total" class="font-weight-bold text-danger">Rp. <?php echo number_format($grand_total, 0, ',', '.') ?></h4>
                    <input type="hidden" name="final_total_hidden" id="final_total_hidden" value="<?php echo $grand_total; ?>">
                    <input type="hidden" name="diskon_nominal_hidden" id="diskon_nominal_hidden" value="0">
                    <input type="hidden" name="applied_voucher_id" id="applied_voucher_id" value="">
                </div>
                
                <div class="form-group">
                    <label>Grand Total Pembayaran (termasuk ongkir):</label>
                    <h4 id="grand_total_pembayaran" class="font-weight-bold text-primary">Rp. <?php echo number_format($grand_total, 0, ',', '.') ?></h4>
                    <input type="hidden" name="final_grand_total_pembayaran" id="final_grand_total_pembayaran" value="<?php echo $grand_total; ?>">
                </div>

                <button type="submit" class="btn btn-sm btn-primary mb-3">Pesan</button>

            </form>
        </div>
        <div class="col-md2"></div>
    </div>
</div>


