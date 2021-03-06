<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800 mb-5"><?= $title; ?></h1>
    <div class="row d-flex justify-content-center">
        <div class="col-lg-11">
            <?= $this->session->flashdata('pesan'); ?>
            <a href="<?= base_url('produk/tambahproduk') ?>" class="btn btn-primary mb-3">Tambah Produk Jam Baru</a>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Nama Jam Tangan</th>
                                    <th scope="col">Harga</th>
                                    <th scope="col">Gambar</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jamtangan as $jm) : ?>
                                    <tr>
                                        <th scope="row"><?= 1 + $start; ?></th>
                                        <td><?= $jm['nama']; ?></td>
                                        <td>Rp. <?= number_format($jm['harga'], 0, ',', '.') ?></td>
                                        <td><?= $jm['gambar']; ?></td>
                                        <td>
                                            <!-- DELETE -->
                                            <a href="<?= base_url(); ?>produk/hapusproduk/<?= $jm['id_produk']; ?>" class="btn btn-danger btn-sm"><i class="fas fa-fw fa-trash-alt"></i></a>
                                            <!-- UPDATE -->
                                            <a href="<?= base_url(); ?>produk/updateproduk/<?= $jm['id_produk']; ?>" class="btn btn-primary btn-sm">
                                                <i class="fas fa-fw fa-pen"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $start++; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <?= $this->pagination->create_links(); ?>
            </div>
        </div>
    </div>

</div>
</div>
<!-- End of Main Content -->

<!-- Modal Tambah Menu -->
<!-- <div class="modal fade" id="tambahproduk" tabindex="-1" aria-labelledby="tambahprodukLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahprodukLabel">Tambah Sub Menu Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div> -->
<!-- <form action="<?php //base_url('produk'); 
                    ?>" method="POST">
        <div class="form-group">
            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Produk Jam" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="harga" name="harga" placeholder="Harga Produk Jam" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Deskripsi Produk Jam" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="gambar" name="gambar" placeholder="Gambar" autocomplete="off">
        </div>
        <div class="form-group">
            <input type="text" class="form-control" id="banner" name="banner" placeholder="Banner Produk Jam" autocomplete="off">
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
</form> -->
<!-- </div>
    </div>
</div> -->