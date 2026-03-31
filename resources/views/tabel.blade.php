@extends('layouts.template')

@section('styles')
    <style>
        body {
            background: #f5f7fb;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(45deg, #0d6efd, #0dcaf0);
            color: white;
            font-weight: 600;
        }

        thead {
            background: #e9f2ff;
        }

        th {
            text-align: center;
        }

        td, th {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }

        tbody tr:hover {
            background: #f1f7ff;
        }
    </style>
@endsection


@section('content')
    <div class="container mt-4">

        <div class="card">

            <div class="card-header">
                <h4>Tabel Data Lokasi</h4>
            </div>

            <div class="card-body">

                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td>1</td>
                            <td>Candi Prambanan</td>
                            <td>Candi Hindu terbesar di Indonesia, dibangun pada abad ke-9 Masehi. [3]</td>
                            <td><img src="https://via.placeholder.com/150/8B4513/FFFFFF?Text=Prambanan" alt="Candi Prambanan" width="100"></td>
                        </tr>

                        <tr>
                            <td>2</td>
                            <td>Keraton Yogyakarta</td>
                            <td>Istana resmi Kesultanan Ngayogyakarta Hadiningrat. [4]</td>
                            <td><img src="https://via.placeholder.com/150/FFD700/000000?Text=Keraton" alt="Keraton Yogyakarta" width="100"></td>
                        </tr>

                        <tr>
                            <td>3</td>
                            <td>Jalan Malioboro</td>
                            <td>Nama salah satu jalan tiga di pusat Kota Yogyakarta. Membentang dari Tugu Yogyakarta hingga ke perempatan Kantor Pos Yogyakarta. [8]</td>
                            <td><img src="https://via.placeholder.com/150/008080/FFFFFF?Text=Malioboro" alt="Jalan Malioboro" width="100"></td>
                        </tr>

                        <tr>
                            <td>4</td>
                            <td>Taman Sari</td>
                            <td>Situs bekas taman atau kebun istana Keraton Ngayogyakarta Hadiningrat. [1, 2]</td>
                            <td><img src="https://via.placeholder.com/150/2E8B57/FFFFFF?Text=Taman+Sari" alt="Taman Sari" width="100"></td>
                        </tr>

                        <tr>
                            <td>5</td>
                            <td>Pantai Parangtritis</td>
                            <td>Pantai di pesisir Samudra Hindia yang terletak di Kabupaten Bantul. [12]</td>
                            <td><img src="https://via.placeholder.com/150/4682B4/FFFFFF?Text=Parangtritis" alt="Pantai Parangtritis" width="100"></td>
                        </tr>

                    </tbody>

                </table>

            </div>
        </div>
    </div>
@endsection
