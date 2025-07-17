<div class="container-fluid">
    <button class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#tambah_voucher"><i class="fas fa-plus fa-sm"></i> Tambah Voucher</button>



    <table class="table table-bordered">
        <tr>
            <th>NO</th>
            <th>KODE VOUCHER</th>
            <th>NILAI DISKON</th>
            <th>TIPE DISKON</th>
            <th>KADALUARSA</th>
            <th>BATAS PENGGUNAAN</th>
            <th>DIGUNAKAN</th>
            <th>STATUS</th>
            <th colspan="2">AKSI</th>
        </tr>

        <?php
        $no=1;
        foreach ($voucher as $vch) : ?>
        <tr>
            <td><?php echo $no++ ?></td>
            <td><?php echo $vch->kode_voucher ?></td>
            <td><?php echo number_format($vch->nilai_diskon, 0, ',', '.') ?></td>
            <td><?php echo ucfirst($vch->tipe_diskon) ?></td>
            <td><?php echo $vch->kadaluarsa ? date('d-m-Y H:i', strtotime($vch->kadaluarsa)) : 'Tidak Terbatas' ?></td>
            <td><?php echo $vch->batas_penggunaan ?: 'Tidak Terbatas' ?></td>
            <td><?php echo $vch->digunakan ?></td>
            <td><?php echo $vch->is_active ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-secondary">Tidak Aktif</span>' ?></td>
            <td><?php echo anchor ('admin/voucher/edit/' . $vch->id_voucher, '<div class="btn btn-primary btn-sm"><i class="fa fa-edit"></i></div>') ?></td>
            <td><?php echo anchor ('admin/voucher/hapus/' . $vch->id_voucher, '<div class="btn btn-danger btn-sm" onclick="return confirm(\'Anda yakin ingin menghapus voucher ini?\')"><i class="fas fa-trash"></i></div>') ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class="modal fade" id="tambah_voucher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title" id="exampleModalLabel">FORM TAMBAH VOUCHER</h2>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php echo form_open('admin/voucher/tambah_aksi'); ?>
          <div class="form-group">
              <label>Kode Voucher</label>
              <input type="text" name="kode_voucher" class="form-control" value="<?php echo set_value('kode_voucher'); ?>">
              <?php echo form_error('kode_voucher', '<div class="text-danger small">', '</div>'); ?>
          </div>
          <div class="form-group">
              <label>Nilai Diskon</label>
              <input type="text" name="nilai_diskon" class="form-control" placeholder="Contoh: 10 atau 50000" value="<?php echo set_value('nilai_diskon'); ?>">
              <?php echo form_error('nilai_diskon', '<div class="text-danger small">', '</div>'); ?>
          </div>
          <div class="form-group">
              <label>Tipe Diskon</label>
              <select class="form-control" name="tipe_diskon">
                <option value="nominal" <?php echo set_select('tipe_diskon', 'nominal'); ?>>Nominal (Rp)</option>
                <option value="persen" <?php echo set_select('tipe_diskon', 'persen'); ?>>Persentase (%)</option>
              </select>
              <?php echo form_error('tipe_diskon', '<div class="text-danger small">', '</div>'); ?>
          </div>
          <div class="form-group">
              <label>Tanggal Kadaluarsa (Opsional)</label>
              <input type="datetime-local" name="kadaluarsa" class="form-control" value="<?php echo set_value('kadaluarsa'); ?>">
          </div>
          <div class="form-group">
              <label>Batas Penggunaan (Opsional, angka)</label>
              <input type="number" name="batas_penggunaan" class="form-control" placeholder="Contoh: 100" value="<?php echo set_value('batas_penggunaan'); ?>">
          </div>
          <div class="form-group form-check">
              <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
              <label class="form-check-label" for="is_active">Aktifkan Voucher</label>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

