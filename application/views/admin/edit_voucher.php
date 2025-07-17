<div class="container-fluid">
    <h3><i class="fas fa-edit"></i> EDIT DATA VOUCHER</h3>

    <?php echo form_open('admin/voucher/update'); ?>
        <input type="hidden" name="id_voucher" value="<?php echo $voucher->id_voucher ?>">

        <div class="form-group">
            <label>Kode Voucher</label>
            <input type="text" name="kode_voucher" class="form-control" value="<?php echo set_value('kode_voucher', $voucher->kode_voucher); ?>">
            <?php echo form_error('kode_voucher', '<div class="text-danger small">', '</div>'); ?>
        </div>
        <div class="form-group">
            <label>Nilai Diskon</label>
            <input type="text" name="nilai_diskon" class="form-control" placeholder="Contoh: 10 atau 50000" value="<?php echo set_value('nilai_diskon', $voucher->nilai_diskon); ?>">
            <?php echo form_error('nilai_diskon', '<div class="text-danger small">', '</div>'); ?>
        </div>
        <div class="form-group">
            <label>Tipe Diskon</label>
            <select class="form-control" name="tipe_diskon">
                <option value="nominal" <?php echo set_select('tipe_diskon', 'nominal', ($voucher->tipe_diskon == 'nominal')); ?>>Nominal (Rp)</option>
                <option value="persen" <?php echo set_select('tipe_diskon', 'persen', ($voucher->tipe_diskon == 'persen')); ?>>Persentase (%)</option>
            </select>
            <?php echo form_error('tipe_diskon', '<div class="text-danger small">', '</div>'); ?>
        </div>
        <div class="form-group">
            <label>Tanggal Kadaluarsa (Opsional)</label>
            <input type="datetime-local" name="kadaluarsa" class="form-control" value="<?php echo set_value('kadaluarsa', ($voucher->kadaluarsa ? date('Y-m-d\TH:i', strtotime($voucher->kadaluarsa)) : '')); ?>">
        </div>
        <div class="form-group">
            <label>Batas Penggunaan (Opsional, angka)</label>
            <input type="number" name="batas_penggunaan" class="form-control" placeholder="Contoh: 100" value="<?php echo set_value('batas_penggunaan', $voucher->batas_penggunaan); ?>">
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_active_edit" name="is_active" value="1" <?php echo ($voucher->is_active == 1 ? 'checked' : ''); ?>>
            <label class="form-check-label" for="is_active_edit">Aktifkan Voucher</label>
        </div>

        <button type="submit" class="btn btn-primary btn-sm mt-3">Update</button>
        <a href="<?php echo base_url('admin/voucher/index') ?>" class="btn btn-danger btn-sm mt-3">Batal</a>
    <?php echo form_close(); ?>
</div>

