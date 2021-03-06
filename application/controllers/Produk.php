<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'Jam Tangan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $user_id = $data['user']['id_user'];
        // $data['jamtangan'] =  $this->db->get('produk_jam')->result_array();

        $this->load->library('pagination');
        // Halaman Pagination
        $config['total_rows'] = $this->modeluser->hitungdataprodukjamuser($user_id);
        $config['base_url'] = 'http://localhost/timezone/produk/index';
        // Total Baris Pagination
        $config['per_page'] = 3;

        // INISIALISASI Pagination
        $this->pagination->initialize($config);
        // END INISIALISASI

        $data['start'] = $this->uri->segment(3);
        $data['jamtangan'] = $this->modeluser->getdatajamuser($config['per_page'], $data['start'], $user_id);
        // END PAGINATION

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar');
        $this->load->view('produk/jamtangan', $data);
        $this->load->view('template/footer');
    }

    public function tambahproduk()
    {
        $data['title'] = 'Tambah Jam Tangan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $user_id = $data['user']['id_user'];
        //   Validation
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', ['required' => 'Nama Produk Harus Diisi']);
        $this->form_validation->set_rules(
            'harga',
            'Harga',
            'trim|required|numeric|min_length[5]',
            [
                'required' => 'Harga Harus Diisi',
                'numeric' => 'Terjadi Kesalahan Input Harga',
                'min_length' => 'Terjadi Kesalahan Input Harga'
            ]
        );
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required', ['required' => 'Deskripsi Harus Diisi']);

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar');
            $this->load->view('produk/tambahjamtangan', $data);
            $this->load->view('template/footer');
        } else {
            // Cek Gambar
            $gambar = $_FILES['gambar']['name'];
            if ($gambar) {
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size']     = '2048';
                $config['upload_path'] = './assets/img/fototoko';
                $this->load->library('upload', $config);

                if (!$this->upload->do_upload('gambar')) {
                    echo "Gagal";
                    die;
                } else $gambar = $this->upload->data('file_name');
            }

            $this->modeluser->tambahprodukjam($gambar, $user_id);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"> <strong>Berhasil</strong> Menambahkan Produk Jam Tangan
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('produk');
        }
    }

    public function hapusproduk($id_produk)
    {
        $this->modeluser->hapusproduk($id_produk);
        $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Berhasil</strong> Hapus Produk
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
        redirect('produk');
    }

    public function updateproduk($id_produk)
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['title'] = 'Edit Produk Jam Tangan';
        $data['jamtangan'] = $this->db->get_where('produk_jam', ['id_produk' => $id_produk])->row_array();

        //   Validation
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', ['required' => 'Nama Produk Harus Diisi']);
        $this->form_validation->set_rules(
            'harga',
            'Harga',
            'trim|required|numeric|min_length[5]',
            [
                'required' => 'Harga Harus Diisi',
                'numeric' => 'Terjadi Kesalahan Input Harga',
                'min_length' => 'Terjadi Kesalahan Input Harga'
            ]
        );
        // $this->form_validation->set_rules('gambar', 'Gambar', 'trim|required', ['required' => 'Gambar Produk Harus Diisi']);
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required', ['required' => 'Deskripsi Harus Diisi']);

        if ($this->form_validation->run() == false) {
            $this->load->view('template/header', $data);
            $this->load->view('template/sidebar');
            $this->load->view('template/topbar');
            $this->load->view('produk/updatejamtangan', $data);
            $this->load->view('template/footer');
        } else {
            $gambar = $_FILES['gambar']['name'];
            $old_gambar = $data['jamtangan']['gambar'];
            if ($gambar) {
                $config['allowed_types'] = 'jpeg|jpg|png';
                $config['max_size']     = '2048';
                $config['upload_path'] = './assets/img/fototoko';
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('gambar')) {
                    unlink(FCPATH . 'assets/img/fototoko/' . $old_gambar);
                    $gambar = $this->upload->data('file_name');
                }
            } else {
                $gambar = $old_gambar;
            }
            $nama = $this->input->post('nama');
            $harga = $this->input->post('harga');
            $deskripsi = $this->input->post('deskripsi');
            $data = [
                "nama" => $nama,
                "harga" => $harga,
                "deskripsi" => $deskripsi,
                "gambar" => $gambar
            ];
            $this->db->where('id_produk', $id_produk);
            $this->db->update('produk_jam', $data);
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Berhasil</strong> Update produk
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
            redirect('produk');
        }
    }
    public function pesanan()
    {
        $data['title'] = 'Pesanan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $agen_id = $data['user']['id_user'];
        // $data['jamtangan'] =  $this->db->get('produk_jam')->result_array();

        $this->load->library('pagination');
        // Halaman Pagination
        $config['total_rows'] = $this->modeluser->getdatapesanan($agen_id);
        $config['base_url'] = 'http://localhost/timezone/produk/pesanan';
        // Total Baris Pagination
        $config['per_page'] = 3;

        // INISIALISASI Pagination
        $this->pagination->initialize($config);
        // END INISIALISASI

        $data['start'] = $this->uri->segment(3);
        $data['pesanan'] = $this->modeluser->tampilpesanan($config['per_page'], $data['start'], $agen_id);
        // END PAGINATION

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar');
        $this->load->view('produk/pesanan', $data);
        $this->load->view('template/footer');
    }

    public function detailpesanan($id)
    {
        $data['title'] = 'Detail Pesanan';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['pesanan'] = $this->modeluser->detailpesanan($id);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar');
        $this->load->view('produk/detailpesanan', $data);
        $this->load->view('template/footer');
    }

    public function setpesanan($id)
    {
        $status = htmlspecialchars($this->input->post('status', true));
        $data['update'] = $this->modeluser->setpesanan($id, $status);
        if ($status == 'Selesai') {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Pesanan Selesai</strong> Ditambahkan Kedalam Invoice
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
        } else {
            $this->session->set_flashdata('pesan', '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Berhasil</strong> Update Status Pesanan
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>');
        }
        redirect('produk/pesanan');
    }


    public function invoice()
    {
        $data['title'] = 'Invoice';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $agen_id = $data['user']['id_user'];

        $this->load->library('pagination');
        // Halaman Pagination
        $config['total_rows'] = $this->modeluser->hitunginvoice($agen_id);
        $config['base_url'] = 'http://localhost/timezone/produk/invoice';
        // Total Baris Pagination
        $config['per_page'] = 3;

        // INISIALISASI Pagination
        $this->pagination->initialize($config);
        // END INISIALISASI

        $data['start'] = $this->uri->segment(3);
        $data['invoice'] = $this->modeluser->getdatainvoice($config['per_page'], $data['start'], $agen_id);
        // END PAGINATION

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar');
        $this->load->view('produk/invoice', $data);
        $this->load->view('template/footer');
    }

    public function detailinvoice($id)
    {
        $data['title'] = 'Detail Invoice';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['invoice'] = $this->modeluser->detailinvoice($id);

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar');
        $this->load->view('template/topbar');
        $this->load->view('produk/detailinvoice', $data);
        $this->load->view('template/footer');
    }
}
