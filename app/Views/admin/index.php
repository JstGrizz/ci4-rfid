<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>

    <link rel="shortcut icon" href="../assets/compiled/svg/favicon.svg" type="image/x-icon" />
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png" />

    <link rel="stylesheet" href="<?= base_url('/assets/extensions/simple-datatables/style.css'); ?>" />

    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/table-datatable.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app-dark.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/iconly.css'); ?>" />
</head>

<body>
    <style>
        #line {
            min-height: 350px;
        }

        .bullet {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .text-center h3 {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .d-flex.justify-content-around {
            border-top: 1px solid #eee;
            padding-top: 1.5rem;
        }
    </style>
    <script src="assets/static/js/initTheme.js"></script>
    <div id="app">
        <?= view('sidebar'); ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <h3>Dashboard</h3>
            </div>
            <div class="page-content">
                <section class="row">
                    <div class="col-12 col-lg-9">
                        <div class="row">
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h4>Data Tanaman</h4>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-striped" id="table1"
                                            style="width: 100%; text-align: center;">
                                            <thead>
                                                <tr>
                                                    <th>PT</th>
                                                    <th>Estate</th>
                                                    <th>Blok</th>
                                                    <th>Varian Bibit</th>
                                                    <th>No Titik Tanam</th>
                                                    <th>RFID</th>
                                                    <th>Status</th>
                                                    <th>Sister</th>
                                                    <th>Umur (Minggu)</th>
                                                    <th>Tipe Aktivitas</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($tanamanData)) : ?>
                                                    <?php foreach ($tanamanData as $data) : ?>
                                                        <tr>
                                                            <td><?= esc($data['pt']) ?></td>
                                                            <td><?= esc($data['estate']) ?></td>
                                                            <td><?= esc($data['blok']) ?></td>
                                                            <td><?= esc($data['varian_bibit']) ?></td>
                                                            <td><?= esc($data['no_titik_tanam']) ?></td>
                                                            <td><?= esc($data['rfid_tanaman']) ?></td>
                                                            <td><?= esc($data['status']) ?></td>
                                                            <td><?= esc($data['sister']) ?></td>
                                                            <td><?= $data['umur'] ?></td>
                                                            <td><?= esc($data['tipe_aktivitas']) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else : ?>
                                                    <tr>
                                                        <td colspan="10">No data found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="card">
                            <div class="card-header">
                                <h4>User Profile</h4>
                            </div>
                            <!-- Kurangi padding vertical dari py-4 menjadi py-2 -->
                            <div class="card-body px-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-xl">
                                        <?php if (isset($karyawan['gender']) && $karyawan['gender'] === 'L'): ?>
                                            <img src="" alt="Avatar" />
                                        <?php elseif (isset($karyawan['gender']) && $karyawan['gender'] === 'P'): ?>
                                            <img src="" alt="Avatar" />
                                        <?php else: ?>
                                            <img src="<?= base_url('assets/static/images/avatar-placeholder.png') ?>"
                                                alt="Avatar Default" />
                                        <?php endif ?>
                                    </div>
                                    <div class="ms-3 name">
                                        <h5 class="font-bold"><?= esc($karyawan['nama']) ?></h5>
                                    </div>
                                </div>

                                <!-- Ganti my-3 menjadi my-2 agar jarak lebih kecil -->
                                <hr class="my-3">

                                <ul class="list-unstyled">
                                    <li>
                                        <strong>NPK:</strong>
                                        <span class="text-muted"><?= esc($karyawan['npk']) ?></span>
                                    </li>
                                    <li>
                                        <strong>Nama:</strong>
                                        <span class="text-muted"><?= esc($karyawan['nama']) ?></span>
                                    </li>
                                    <li>
                                        <strong>Status Karyawan:</strong>
                                        <span class="text-muted"><?= esc($karyawan['status_karyawan']) ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Tanaman <?= date('Y') ?></h4>
                            </div>
                            <div class="card-body">
                                <div id="line"></div>

                                <div class="d-flex justify-content-around mt-4">
                                    <div class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="bullet me-2"
                                                style="background-color: #4361ee; width: 10px; height: 10px; border-radius: 50%; display: inline-block;"></span>
                                            <span>Seleksi</span>
                                        </div>
                                        <h3 class="mb-0 mt-1"><?= $countSeleksi ?></h3>
                                    </div>

                                    <div class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="bullet me-2"
                                                style="background-color: #06d6a0; width: 10px; height: 10px; border-radius: 50%; display: inline-block;"></span>
                                            <span>Shooting</span>
                                        </div>
                                        <h3 class="mb-0 mt-1"><?= $countShooting ?></h3>
                                    </div>

                                    <div class="text-center">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <span class="bullet me-2"
                                                style="background-color: #ef476f; width: 10px; height: 10px; border-radius: 50%; display: inline-block;"></span>
                                            <span>Losses</span>
                                        </div>
                                        <h3 class="mb-0 mt-1"><?= $countLosses ?></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <?= view('footer'); ?>
        </div>
    </div>
    <script src="<?= base_url('/assets/static/js/components/dark.js'); ?>"></script>
    <script src="<?= base_url('/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js'); ?>"></script>

    <script src="<?= base_url('/assets/compiled/js/app.js'); ?>"></script>

    <script src="<?= base_url('/assets/extensions/simple-datatables/umd/simple-datatables.js'); ?>"></script>
    <script src="<?= base_url('/assets/static/js/pages/simple-datatables.js'); ?>"></script>
    <script src=" <?= base_url('/assets/static/js/pages/simple-datatables.js'); ?>"></script>

    <!-- Need: Apexcharts -->
    <script src="../assets/extensions/apexcharts/apexcharts.min.js"></script>
    <script src="../assets/static/js/pages/dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Prepare data
            const months = <?= json_encode($months) ?>;
            const seleksiData = <?= json_encode(array_values($monthlyData['seleksi'] ?? [])) ?>;
            const shootingData = <?= json_encode(array_values($monthlyData['shooting'] ?? [])) ?>;
            const lossesData = <?= json_encode(array_values($monthlyData['losses'] ?? [])) ?>;

            // Initialize the chart
            const options = {
                series: [{
                        name: "Seleksi",
                        data: seleksiData,
                        color: '#4361ee'
                    },
                    {
                        name: "Shooting",
                        data: shootingData,
                        color: '#06d6a0'
                    },
                    {
                        name: "Losses",
                        data: lossesData,
                        color: '#ef476f'
                    }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    zoom: {
                        enabled: false
                    },
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                grid: {
                    row: {
                        colors: ['#f3f3f3', 'transparent'],
                        opacity: 0.5
                    },
                },
                xaxis: {
                    categories: months,
                    labels: {
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    min: 0,
                    title: {
                        text: 'Jumlah Tanaman'
                    },
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    fontSize: '14px',
                    itemMargin: {
                        horizontal: 20,
                        vertical: 5
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " tanaman";
                        }
                    }
                },
                markers: {
                    size: 5,
                    strokeWidth: 0,
                    hover: {
                        size: 7
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#line"), options);
            chart.render();
        });
    </script>
</body>

</html>