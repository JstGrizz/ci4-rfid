<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laporan Identifikasi Tanaman</title>

    <link rel="shortcut icon" href="<?= base_url('/assets/compiled/svg/favicon.svg'); ?>" type="image/x-icon" />
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png" />

    <link rel="stylesheet" href="<?= base_url('/assets/extensions/simple-datatables/style.css'); ?>" />

    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/table-datatable.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app-dark.css'); ?>" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <style>
        .table-centered th,
        .table-centered td {
            text-align: center;
            justify-content: center;
        }
    </style>
    <script src="<?= base_url('/assets/static/js/initTheme.js'); ?>"></script>
    <div id="app">
        <?= view('sidebar'); ?>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>Laporan Identifikasi Tanaman</h3>
                            <p class="text-subtitle text-muted">
                                Form untuk Laporan Identifikasi Tanaman
                            </p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="<?= base_url('admin/dashboard'); ?>">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Laporan Identifikasi Tanaman
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <h4>Laporan Identifikasi Tanaman</h4>
                        </div>
                        <div class="card-body">
                            <form id="reportForm">
                                <div class="row">
                                    <div class="form-group">
                                        <label for="start_date">Tanggal Awal</label>
                                        <input type="date" id="start_date" name="start_date" class="form-control"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="end_date">Tanggal Akhir</label>
                                        <input type="date" id="end_date" name="end_date" class="form-control" required>
                                    </div>
                                    <div class="col-md">
                                        <label for="report_type">Laporan Berdasarkan</label>
                                        <select id="report_type" name="report_type" class="form-control">
                                            <option value="">- Pilih Berdasarkan apa -</option>
                                            <optgroup label="Losses">
                                                <option value="losses_estate">PT + Estate</option>
                                                <option value="losses_block">PT + Estate + Blok</option>
                                                <option value="losses_titik">PT + Estate + Blok + Titik Tanam</option>
                                            </optgroup>

                                            <optgroup label="Recovery">
                                                <option value="recovery_estate">PT + Estate</option>
                                                <option value="recovery_block">PT + Estate + Blok</option>
                                                <option value="recovery_titik">PT + Estate + Blok + Titik Tanam</option>
                                            </optgroup>
                                        </select>

                                    </div>
                                    <div class="col-md">
                                        <br>
                                        <button type="button" id="generateReportBtn"
                                            class="btn btn-primary me-1 mb-1 w-100">
                                            Generate Laporan Identifikasi Tanaman
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- DOWNLOAD BUTTONS (hidden until data loads) -->
                            <div id="download-buttons" class="mt-3 mb-3" style="display: none;">
                                <a id="download-excel" class="btn btn-success me-2">
                                    <i class="bi bi-file-earmark-spreadsheet-fill"></i> Download Excel
                                </a>
                                <a id="download-pdf" class="btn btn-danger">
                                    <i class="bi bi-file-earmark-pdf-fill"></i> Download PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="multiple-column-form">
                    <div class="row match-height">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Data Laporan Identifikasi Tanaman</h4>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped" id="reportTable"
                                        style="width:100%; text-align:center;">
                                        <thead id="reportTableHeader"></thead>
                                        <tbody id="dataResults"></tbody>
                                    </table>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const baseUrl = '<?= base_url() ?>/';

        // 1) Bangun header tabel sesuai tipe laporan
        function renderTableHeader(type) {
            const map = {
                losses_estate: ['PT', 'Estate', 'Jumlah Losses'],
                losses_block: ['PT', 'Estate', 'Blok', 'Jumlah Losses'],
                losses_titik: ['PT', 'Estate', 'Blok', 'Titik Tanam', 'Jumlah Losses'],
                recovery_estate: ['PT', 'Estate', 'Jumlah Recovery'],
                recovery_block: ['PT', 'Estate', 'Blok', 'Jumlah Recovery'],
                recovery_titik: ['PT', 'Estate', 'Blok', 'Titik Tanam', 'Jumlah Recovery'],
            };
            const headers = map[type] || [];
            const $tr = $('<tr>');
            headers.forEach(h => $tr.append(`<th>${h}</th>`));
            $('#reportTableHeader').html($tr);
        }

        // 2) Fetch & render data
        function fetchReportData() {
            const start = $('#start_date').val(),
                end = $('#end_date').val(),
                type = $('#report_type').val();

            if (!start || !end || !type) {
                return alert('Mohon lengkapi semua pilihan.');
            }

            $.getJSON(`${baseUrl}identifikasi-tanaman/fetchReportIdentifikasiTanaman`, {
                    start_date: start,
                    end_date: end,
                    report_type: type
                })
                .done(resp => {
                    renderTableHeader(type);
                    const $body = $('#dataResults').empty();

                    if (!resp.length) {
                        const colspan = $('#reportTableHeader th').length || 1;
                        $body.html(`<tr><td colspan="${colspan}" class="text-center">Tidak ada data</td></tr>`);
                    } else {
                        resp.forEach(d => {
                            let cells;
                            if (type.startsWith('losses')) {
                                if (type === 'losses_estate') {
                                    cells = [d.pt, d.estate, d.jumlah_losses];
                                } else if (type === 'losses_block') {
                                    cells = [d.pt, d.estate, d.nama_blok, d.jumlah_losses];
                                } else {
                                    cells = [d.pt, d.estate, d.nama_blok, d.no_titik_tanam, d.jumlah_losses];
                                }
                            } else {
                                if (type === 'recovery_estate') {
                                    cells = [d.pt, d.estate, d.jumlah_recovery];
                                } else if (type === 'recovery_block') {
                                    cells = [d.pt, d.estate, d.nama_blok, d.jumlah_recovery];
                                } else {
                                    cells = [d.pt, d.estate, d.nama_blok, d.no_titik_tanam, d.jumlah_recovery];
                                }
                            }

                            const $tr = $('<tr>');
                            cells.forEach(c => $tr.append(`<td>${c}</td>`));
                            $body.append($tr);
                        });
                    }

                    // 3) Setup tombol download
                    const params = $.param({
                        start_date: start,
                        end_date: end,
                        report_type: type
                    });
                    $('#download-excel').attr('href',
                        `${baseUrl}identifikasi-tanaman/downloadIdentifikasiTanamanExcel?${params}`);
                    $('#download-pdf').attr('href',
                        `${baseUrl}identifikasi-tanaman/downloadIdentifikasiTanamanPdf?${params}`);
                    $('#download-buttons').show();
                })
                .fail(() => alert('Gagal mengambil data laporan.'));
        }

        // 4) Hook tombol generate
        $(function() {
            $('#generateReportBtn').on('click', e => {
                e.preventDefault();
                fetchReportData();
            });
        });
    </script>
</body>

</html>