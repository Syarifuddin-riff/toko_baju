<div class="container-fluid">
    <h4>Keranjang Belanja</h4>

    <?php
    // Tampilkan flashdata jika ada, dan pastikan sudah dihapus setelahnya
    if ($this->session->flashdata('pesan')) {
        echo $this->session->flashdata('pesan');
        $this->session->unset_userdata('pesan'); // Menghapus flashdata secara manual setelah ditampilkan
    }
    ?>
    
    <?php 
    if ($this->cart->total_items() > 0) : 
    ?>
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th>NO</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Sub-Total</th>
                <th>Opsi</th> 
            </tr>

            <?php 
            $no=1;
            foreach ($this->cart->contents() as $items) : ?>

                <tr>
                    <td><?php echo $no++ ?></td>
                    <td><?php echo $items['name'] ?></td>
                    <td><?php echo $items['qty'] ?></td>
                    <td align="right">Rp. <?php echo number_format ($items['price'], 0,',','.')  ?></td>
                    <td align="right">Rp. <?php echo number_format ($items['subtotal'], 0,',','.')  ?></td>
                    <td>
                        <a href="<?php echo base_url('dashboard/hapus_item_keranjang/' . $items['rowid']) ?>" 
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Anda yakin ingin menghapus produk <?php echo $items['name']; ?> dari keranjang?')">
                           <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>

            <?php endforeach; ?>

            <tr>
                <td colspan="5"></td> <td align="right">Rp. <?php echo number_format ($this->cart->total(), 0,',','.')  ?></td>
            </tr>
        </table>
        
        <div align="right">
            <a href="<?php echo base_url('dashboard/hapus_keranjang') ?>" 
               class="btn btn-sm btn-danger"
               onclick="return confirm('Anda yakin ingin menghapus semua produk dari keranjang?')">
               Hapus Keranjang
            </a>
            <a href="<?php echo base_url('welcome') ?>"><div class="btn btn-sm btn-primary">Lanjutkan Belanja</div></a>
            <a href="<?php echo base_url('dashboard/pembayaran') ?>"><div class="btn btn-sm btn-success">Pembayaran</div></a> 
        </div>
    <?php else : ?>
        <div class="alert alert-info" role="alert">
            Keranjang belanja Anda saat ini kosong. Silakan tambahkan produk terlebih dahulu.
        </div>
        <div align="right">
            <a href="<?php echo base_url('dashboard/hapus_keranjang') ?>" class="btn btn-sm btn-danger disabled" aria-disabled="true">Hapus Keranjang</a> 
            <a href="<?php echo base_url('welcome') ?>" class="btn btn-sm btn-primary">Lanjutkan Belanja</a>
            <button class="btn btn-sm btn-success disabled" disabled>Pembayaran</button>
        </div>
    <?php endif; ?>
</div>

