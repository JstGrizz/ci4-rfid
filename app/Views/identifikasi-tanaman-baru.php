<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Identifikasi Tanaman - Baru</title>

    <link rel="shortcut icon" href="<?= base_url('/assets/compiled/svg/favicon.svg'); ?>" type="image/x-icon" />
    <link rel="shortcut icon"
        href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACEAAAAiCAYAAADRcLDBAAAEs2lUWHRYTUw6Y29tLmFkb2JlLnhtcAAAAAAAPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4KPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iWE1QIENvcmUgNS41LjAiPgogPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4KICA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIgogICAgeG1sbnM6ZXhpZj0iaHR0cDovL25zLmFkb2JlLmNvbS9leGlmLzEuMC8iCiAgICB4bWxuczp0aWZmPSJodHRwOi8vbnMuYWRvYmUuY29tL3RpZmYvMS4wLyIKICAgIHhtbG5zOnBob3Rvc2hvcD0iaHR0cDovL25zLmFkb2JlLmNvbS9waG90b3Nob3AvMS4wLyIKICAgIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIKICAgIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIgogICAgeG1sbnM6c3RFdnQ9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZUV2ZW50IyIKICAgZXhpZjpQaXhlbFhEaW1lbnNpb249IjMzIgogICBleGlmOlBpeGVsWURpbWVuc2lvbj0iMzQiCiAgIGV4aWY6Q29sb3JTcGFjZT0iMSIKICAgdGlmZjpJbWFnZVdpZHRoPSIzMyIKICAgdGlmZjpJbWFnZUxlbmd0aD0iMzQiCiAgIHRpZmY6UmVzb2x1dGlvblVuaXQ9IjIiCiAgIHRpZmY6WFJlc29sdXRpb249Ijk2LjAiCiAgIHRpZmY6WVJlc29sdXRpb249Ijk2LjAiCiAgIHBob3Rvc2hvcDpDb2xvck1vZGU9IjMiCiAgIHBob3Rvc2hvcDpJQ0NQcm9maWxlPSJzUkdCIElFQzYxOTY2LTIuMSIKICAgeG1wOk1vZGlmeURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiCiAgIHhtcDpNZXRhZGF0YURhdGU9IjIwMjItMDMtMzFUMTA6NTA6MjMrMDI6MDAiPgogICA8eG1wTU06SGlzdG9yeT4KICAgIDxyZGY6U2VxPgogICAgIDxyZGY6bGkKICAgICAgc3RFdnQ6YWN0aW9uPSJwcm9kdWNlZCIKICAgICAgc3RFdnQ6c29mdHdhcmVBZ2VudD0iQWZmaW5pdHkgRGVzaWduZXIgMS4xMC4xIgogICAgICBzdEV2dDp3aGVuPSIyMDIyLTAzLTMxVDEwOjUwOjIzKzAyOjAwIi8+CiAgICA8L3JkZjpTZXE+CiAgIDwveG1wTU06SGlzdG9yeT4KICA8L3JkZjpEZXNjcmlwdGlvbj4KIDwvcmRmOlJERj4KPC94OnhtcG1ldGE+Cjw/eHBhY2tldCBlbmQ9InIiPz5V57uAAAABgmlDQ1BzUkdCIElFQzYxOTY2LTIuMQAAKJF1kc8rRFEUxz9maORHo1hYKC9hISNGTWwsRn4VFmOUX5uZZ36oeTOv954kW2WrKLHxa8FfwFZZK0WkZClrYoOe87ypmWTO7dzzud97z+nec8ETzaiaWd4NWtYyIiNhZWZ2TvE946WZSjqoj6mmPjE1HKWkfdxR5sSbgFOr9Ll/rXoxYapQVik8oOqGJTwqPL5i6Q5vCzeo6dii8KlwpyEXFL519LjLLw6nXP5y2IhGBsFTJ6ykijhexGra0ITl5bRqmWU1fx/nJTWJ7PSUxBbxJkwijBBGYYwhBgnRQ7/MIQIE6ZIVJfK7f/MnyUmuKrPOKgZLpEhj0SnqslRPSEyKnpCRYdXp/9++msneoFu9JgwVT7b91ga+LfjetO3PQ9v+PgLvI1xkC/m5A+h7F32zoLXug38dzi4LWnwHzjeg8UGPGbFfySvuSSbh9QRqZ6H+Gqrm3Z7l9zm+h+iafNUV7O5Bu5z3L/wAdthn7QIme0YAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAJTSURBVFiF7Zi9axRBGIefEw2IdxFBRQsLWUTBaywSK4ubdSGVIY1Y6HZql8ZKCGIqwX/AYLmCgVQKfiDn7jZeEQMWfsSAHAiKqPiB5mIgELWYOW5vzc3O7niHhT/YZvY37/swM/vOzJbIqVq9uQ04CYwCI8AhYAlYAB4Dc7HnrOSJWcoJcBS4ARzQ2F4BZ2LPmTeNuykHwEWgkQGAet9QfiMZjUSt3hwD7psGTWgs9pwH1hC1enMYeA7sKwDxBqjGnvNdZzKZjqmCAKh+U1kmEwi3IEBbIsugnY5avTkEtIAtFhBrQCX2nLVehqyRqFoCAAwBh3WGLAhbgCRIYYinwLolwLqKUwwi9pxV4KUlxKKKUwxC6ZElRCPLYAJxGfhSEOCz6m8HEXvOB2CyIMSk6m8HoXQTmMkJcA2YNTHm3congOvATo3tE3A29pxbpnFzQSiQPcB55IFmFNgFfEQeahaAGZMpsIJIAZWAHcDX2HN+2cT6r39GxmvC9aPNwH5gO1BOPFuBVWAZue0vA9+A12EgjPadnhCuH1WAE8ivYAQ4ohKaagV4gvxi5oG7YSA2vApsCOH60WngKrA3R9IsvQUuhIGY00K4flQG7gHH/mLytB4C42EgfrQb0mV7us8AAMeBS8mGNMR4nwHamtBB7B4QRNdaS0M8GxDEog7iyoAguvJ0QYSBuAOcAt71Kfl7wA8DcTvZ2KtOlJEr+ByyQtqqhTyHTIeB+ONeqi3brh+VgIN0fohUgWGggizZFTplu12yW8iy/YLOGWMpDMTPXnl+Az9vj2HERYqPAAAAAElFTkSuQmCC"
        type="image/png" />

    <link rel="stylesheet" href="<?= base_url('/assets/extensions/simple-datatables/style.css'); ?>" />

    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/table-datatable.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('/assets/compiled/css/app-dark.css'); ?>" />
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
                            <h3>Identifikasi Tanaman Baru</h3>
                            <p class="text-subtitle text-muted">
                                Form untuk mengidentifikasi tanaman Baru
                            </p>
                        </div>
                        <div class="col-12 col-md-6 order-md-2 order-first">
                            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="<?= base_url('admin/dashboard'); ?>">Dashboard</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Identifikasi Tanaman
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <form class="form" id="form-identifikasi-tanaman-baru">
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h4>Data Karyawan</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="npk">NPK</label>
                                    <input type="text" id="npk" class="form-control" name="npk"
                                        value="<?= isset($npk) ? $npk : ''; ?>" readonly />
                                </div>
                                <div class="form-group">
                                    <label for="nama">Nama Karyawan</label>
                                    <input type="text" id="nama" class="form-control" name="nama"
                                        value="<?= isset($nama) ? $nama : ''; ?>" readonly />
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tipe Aktivitas</h4>
                                <p>Masukkan Tipe Aktivitas</p>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="tipe_aktivitas">Tipe Aktivitas</label>
                                    <select class="form-control" id="tipe_aktivitas" name="tipe_aktivitas"
                                        onchange="toggleCards()">
                                        <option value="">- Pilih Tipe Aktivitas -</option>
                                        <?php foreach ($tipeAktivitas as $aktivitas): ?>
                                            <?php
                                            // Convert to lowercase to make comparison case-insensitive
                                            $aktivitas_name = strtolower($aktivitas['nama_aktivitas']);
                                            if ($aktivitas_name === 'seleksi' || $aktivitas_name === 'shooting'): ?>
                                                <option value="<?= $aktivitas['aktivitas_id']; ?>">
                                                    <?= $aktivitas['nama_aktivitas']; ?>
                                                </option>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section id="multiple-column-form">

                        <div class="row match-height">
                            <!-- Card for Hectar Statement Data -->
                            <div class="col-12" id="hectare-statement-card" style="display:none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Hectar Statement</h4>
                                        <p>Masukkan Data Hectar Statement</p>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <!-- PT & Estate Dropdown -->
                                                <div class="form-group">
                                                    <label for="pt_estate">PT & Estate</label>
                                                    <select class="form-control" id="pt_estate" name="pt_estate"
                                                        onchange="updateBloks()">
                                                        <option value="">Select PT and Estate</option>
                                                        <?php foreach ($ptEstates as $ptEstate): ?>
                                                            <option value="<?= $ptEstate['pt_estate_id']; ?>">
                                                                <?= $ptEstate['pt']; ?> - <?= $ptEstate['estate']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>

                                                <!-- Blok Selection -->
                                                <div class="form-group">
                                                    <label for="blok_id">Nama Blok</label>
                                                    <select class="form-control" id="blok_id" name="blok_id">
                                                        <option value="">Select Blok</option>
                                                    </select>
                                                </div>

                                                <!-- Umur Tanam (Minggu) -->
                                                <div class="form-group">
                                                    <label for="week">Umur Tanam (Minggu)</label>
                                                    <input type="text" id="week" class="form-control" name="week"
                                                        readonly />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <!-- Tahun Tanam -->
                                                <div class="form-group">
                                                    <label for="tahun_tanam">Tahun Tanam</label>
                                                    <input type="text" id="tahun_tanam" class="form-control"
                                                        name="tahun_tanam" readonly />
                                                </div>

                                                <!-- Bulan Tanam -->
                                                <div class="form-group">
                                                    <label for="bulan_tanam">Bulan Tanam</label>
                                                    <input type="text" id="bulan_tanam" class="form-control"
                                                        name="bulan_tanam" readonly />
                                                </div>

                                                <!-- Luas Tanah (Ha) -->
                                                <div class="form-group">
                                                    <label for="luas_tanah">Luas Tanah (Ha)</label>
                                                    <input type="text" id="luas_tanah" class="form-control"
                                                        name="luas_tanah" readonly />
                                                </div>

                                                <!-- Varian Bibit -->
                                                <div class="form-group">
                                                    <label for="varian_bibit">Varian Bibit</label>
                                                    <input type="text" id="varian_bibit" class="form-control"
                                                        name="varian_bibit" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card for Identifikasi Tanaman Data -->
                            <div class="col-12" id="identifikasi-tanaman-card" style="display:none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Data Identifikasi Tanaman</h4>
                                        <p>Masukkan Data Identifikasi Tanaman</p>
                                    </div>

                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="no_titik_tanam">No Titik Tanam</label>
                                                    <input type="text" id="no_titik_tanam" class="form-control"
                                                        name="no_titik_tanam" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="longitude">Titik Tanaman Longitude</label>
                                                    <input type="text" id="longitude" class="form-control"
                                                        name="longitude" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="latitude">Titik Tanaman Latitude</label>
                                                    <input type="text" id="latitude" class="form-control"
                                                        name="latitude" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="status">Status Tanaman</label>
                                                    <select class="form-select" id="status" name="status">
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="sister_ke">Sister ke-</label>
                                                    <input type="text" id="sister_ke" class="form-control"
                                                        name="sister_ke" readonly />
                                                </div>
                                                <div class="form-group">
                                                    <label for="rfid">RFID Tanaman</label>
                                                    <input type="text" id="rfid" class="form-control" name="rfid"
                                                        disabled />
                                                </div>
                                            </div>

                                            <div class="col-12 d-flex justify-content-end">
                                                <button type="button" id="submit-seleksi"
                                                    class="btn btn-primary me-1 mb-1">Submit Seleksi</button>
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- New Card for Shooting Activity -->
                            <div class="col-12" id="shooting-card" style="display:none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Mencari Data Identifikasi Tanaman</h4>
                                        <p>Masukkan No Titik Tanam untuk mencari data tanaman</p>
                                    </div>

                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="no_titik_tanam_shooting">No Titik Tanam</label>
                                            <input type="text" id="no_titik_tanam_shooting" class="form-control"
                                                name="no_titik_tanam_shooting" />
                                        </div>
                                        <div class="form-group">
                                            <label for="longitude_shooting">Titik Tanaman Longitude</label>
                                            <input type="text" id="longitude_shooting" class="form-control"
                                                name="longitude_shooting" />
                                        </div>
                                        <div class="form-group">
                                            <label for="latitude_shooting">Titik Tanaman Latitude</label>
                                            <input type="text" id="latitude_shooting" class="form-control"
                                                name="latitude_shooting" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Container to display fetched tanaman data for shooting -->
                                <div id="tanaman-container-shooting"></div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="button" id="submit-shooting" class="btn btn-primary me-1 mb-1">Submit
                                        Shooting</button>
                                </div>
                            </div>
                        </div>
                    </section>
                </form>
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
    <script>
        // 1. PT & Estate → Blok
        function updateBloks() {
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokSelect = document.getElementById('blok_id');
            if (!ptEstateId) {
                blokSelect.innerHTML = '<option value="">Select Blok</option>';
                return;
            }
            fetch(`<?= base_url('identifikasi-tanaman/getBloksByPtEstateId'); ?>/${ptEstateId}`)
                .then(r => r.json())
                .then(data => {
                    blokSelect.innerHTML = '<option value="">Select Blok</option>';
                    data.bloks.forEach(b => {
                        const opt = document.createElement('option');
                        opt.value = b.blok_id;
                        opt.textContent = b.nama_blok;
                        blokSelect.appendChild(opt);
                    });
                    autoFillFields();
                })
                .catch(console.error);
        }

        // 2. Blok → Hectare statement fields
        function autoFillFields() {
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            if (!ptEstateId || !blokId) return;
            fetch(
                    `<?= base_url('identifikasi-tanaman/getHectareStatementByPtEstateIdAndBlockId'); ?>/${ptEstateId}/${blokId}`
                )
                .then(r => r.json())
                .then(data => {
                    if (!data) return;
                    document.getElementById('tahun_tanam').value = data.tahun_tanam;
                    document.getElementById('bulan_tanam').value = data.bulan_tanam;
                    document.getElementById('luas_tanah').value = data.luas_tanah;
                    document.getElementById('varian_bibit').value = data.varian_bibit;
                    document.getElementById('week').value = data.week;
                })
                .catch(console.error);
        }

        // 3. No. Titik Tanam → coords & status (seleksi vs shooting)
        function autoFillTitikTanam() {
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            const noSeleksi = document.getElementById('no_titik_tanam').value;
            const noShoot = document.getElementById('no_titik_tanam_shooting').value;
            const toUse = noShoot || noSeleksi;
            const lonF = document.getElementById('longitude');
            const latF = document.getElementById('latitude');
            const lonFS = document.getElementById('longitude_shooting');
            const latFS = document.getElementById('latitude_shooting');

            // if shooting, make those fields editable
            if (noShoot) {
                lonFS.readOnly = latFS.readOnly = false;
            }

            if (!toUse || !ptEstateId || !blokId) {
                if (!noShoot) {
                    lonF.value = latF.value = '';
                    lonF.readOnly = latF.readOnly = false;
                }
                autoFillStatus();
                return;
            }

            fetch(`<?= base_url('identifikasi-tanaman/getNoTitikTanamData'); ?>/${toUse}/${ptEstateId}/${blokId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.found) {
                        if (!noShoot) {
                            lonF.value = data.longitude;
                            latF.value = data.latitude;
                            lonF.readOnly = latF.readOnly = true;
                        } else {
                            lonFS.value = data.longitude;
                            latFS.value = data.latitude;
                            lonFS.readOnly = latFS.readOnly = true;
                        }
                    } else if (!noShoot) {
                        lonF.value = latF.value = '';
                        lonF.readOnly = latF.readOnly = false;
                    }
                    autoFillStatus();
                })
                .catch(err => {
                    console.error(err);
                    autoFillStatus();
                });
        }

        // 4. Fill status dropdown
        function autoFillStatus() {
            const noSeleksi = document.getElementById('no_titik_tanam').value;
            const ptEstateId = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            const lon = document.getElementById('longitude').value;
            const lat = document.getElementById('latitude').value;
            const sel = document.getElementById('status');

            if (noSeleksi && ptEstateId && blokId && lon && lat) {
                fetch(
                        `<?= base_url('identifikasi-tanaman/getTanamanStatus'); ?>/${noSeleksi}/${ptEstateId}/${blokId}/${lon}/${lat}`
                    )
                    .then(r => r.json())
                    .then(data => {
                        sel.innerHTML = '<option value="">Pilih Status</option>';
                        if (data.success) {
                            data.statusOptions.forEach(s => {
                                const o = document.createElement('option');
                                o.value = s.value;
                                o.textContent = s.label;
                                sel.appendChild(o);
                            });
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(console.error);
            } else {
                // default: only “PC”
                fetch('<?= base_url('master-status/data'); ?>')
                    .then(r => r.json())
                    .then(data => {
                        sel.innerHTML = '<option value="">Pilih Status</option>';
                        (data.status || []).forEach(s => {
                            if (s.nama_status.toLowerCase() === 'pc') {
                                const o = document.createElement('option');
                                o.value = s.status_id;
                                o.textContent = s.nama_status;
                                sel.appendChild(o);
                            }
                        });
                    })
                    .catch(console.error);
            }
        }

        // 5. Fetch & recommend “sisterKe” when status changes or No. Titik loses focus
        function fetchSisterData() {
            const noTT = document.getElementById('no_titik_tanam').value;
            const ptEstate = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            const curStat = document.getElementById('status').value;
            if (!noTT || !ptEstate || !blokId || !curStat) return console.warn('Missing for sister');
            const params = new URLSearchParams({
                noTitikTanam: noTT,
                ptEstateId: ptEstate,
                blokId,
                currentStatusId: curStat
            });
            fetch(`<?= base_url('identifikasi-tanaman/fetchSister'); ?>?${params}`)
                .then(r => r.json())
                .then(d => {
                    if (d.success) document.getElementById('sister_ke').value = d.sister;
                    else console.warn('Sister error', d.error);
                })
                .catch(console.error);
        }

        // 6. Submit handlers
        function insertTanamanDataSeleksi() {
            const f = new FormData(document.querySelector('form'));
            f.append('tipe_aktivitas', document.getElementById('tipe_aktivitas').value);
            f.append('status', document.getElementById('status').value);
            fetch('<?= base_url('identifikasi-tanaman/insertTanamanDataSeleksi'); ?>', {
                method: 'POST',
                body: f
            }).then(r => r.json()).then(d => {
                alert(d.success ? d.message : (d.error || 'Gagal'));
                if (d.success) window.location.reload();
            }).catch(console.error);
        }

        function insertTanamanDataShooting() {
            const noShoot = document.getElementById('no_titik_tanam_shooting').value;
            const ptEstate = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            if (!noShoot || !ptEstate || !blokId) return alert('Lengkapi semua bidang.');
            const f = new FormData(document.querySelector('form'));
            f.append('no_titik_tanam', noShoot);
            f.append('pt_estate', ptEstate);
            f.append('blok_id', blokId);
            fetch('<?= base_url('identifikasi-tanaman/insertTanamanDataShooting'); ?>', {
                method: 'POST',
                body: f
            }).then(r => r.json()).then(d => {
                alert(d.success ? d.message : (d.error || 'Gagal'));
                if (d.success) window.location.reload();
            }).catch(console.error);
        }

        // 7. Toggle cards & shooting list
        function toggleCards() {
            const txt = document.getElementById('tipe_aktivitas')
                .selectedOptions[0].text.toLowerCase();
            document.getElementById('hectare-statement-card').style.display = 'block';
            document.getElementById('identifikasi-tanaman-card').style.display = txt === 'seleksi' ? 'block' : 'none';
            document.getElementById('shooting-card').style.display = txt === 'shooting' ? 'block' : 'none';
            if (txt === 'shooting') {
                autoFillTitikTanam();
                autoFillTanamanData();
            } else {
                document.getElementById('rfid').value = '';
            }
        }

        function autoFillTanamanData() {
            const noShoot = document.getElementById('no_titik_tanam_shooting').value;
            const ptEstate = document.getElementById('pt_estate').value;
            const blokId = document.getElementById('blok_id').value;
            const tipe = document.getElementById('tipe_aktivitas').value;
            const cont = document.getElementById('tanaman-container-shooting');
            if (!noShoot || !ptEstate || !blokId || !tipe) return;
            cont.innerHTML = 'Loading…';
            const url = `<?= base_url('identifikasi-tanaman/getActiveTanamanDataSeleksi'); ?>/${noShoot}` +
                `?pt_estate_id=${ptEstate}&blok_id=${blokId}&tipe_aktivitas=${tipe}`;
            fetch(url).then(r => r.json()).then(d => {
                if (!d.success) return cont.textContent = 'Tidak ada tanaman…';
                cont.innerHTML = '';
                d.tanaman.forEach((t, i) => {
                    cont.innerHTML += `
          <div class="card mb-2">
            <div class="card-header">Tanaman ${i+1}</div>
            <div class="card-body">
              <div>
                <label>RFID</label>
                <input readonly class="form-control" name="rfid_tanaman[${i}]" value="${t.rfid_tanaman||''}">
                <input type="hidden" name="tanaman_id[${i}]" value="${t.tanaman_id}">
              </div>
              <div>
                <label><input type="checkbox" class="form-check-input" name="update_rfid[${i}]" id="upd${i}" onchange="toggleNewRfid(${i})"> Perbarui RFID?</label>
                <div id="updF${i}" style="display:none;">
                  <input class="form-control" name="new_rfid[${i}]" placeholder="Masukkan RFID baru">
                </div>
              </div>
              <div>
                <label>Sister</label>
                <input readonly class="form-control" name="sister[${i}]" value="${t.sister}">
              </div>
              <div>
                <label>Status</label>
                <input readonly class="form-control" name="status[${i}]" value="${t.nama_status}">
              </div>
            </div>
          </div>`;
                });
            }).catch(err => {
                console.error(err);
                cont.textContent = 'Error loading.';
            });
        }

        function toggleNewRfid(i) {
            document.getElementById(`updF${i}`)
                .style.display = document.getElementById(`upd${i}`).checked ? 'block' : 'none';
        }

        // ─── Wire up all listeners ─────────────────────
        document.getElementById('pt_estate').addEventListener('change', updateBloks);
        document.getElementById('blok_id').addEventListener('change', autoFillFields);
        document.getElementById('no_titik_tanam').addEventListener('change', autoFillTitikTanam);
        document.getElementById('no_titik_tanam').addEventListener('blur', fetchSisterData);
        document.getElementById('status').addEventListener('change', fetchSisterData);
        document.getElementById('no_titik_tanam_shooting').addEventListener('change', () => {
            autoFillTitikTanam();
            autoFillTanamanData();
        });
        document.getElementById('submit-seleksi').addEventListener('click', insertTanamanDataSeleksi);
        document.getElementById('submit-shooting').addEventListener('click', insertTanamanDataShooting);
        document.addEventListener('DOMContentLoaded', toggleCards);
    </script>
    <script>
        // once the DOM is ready...
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-identifikasi-tanaman-baru');
            form.addEventListener('keydown', function(e) {
                // if the key is Enter, prevent its default behavior
                if (e.key === 'Enter' || e.keyCode === 13) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
</body>

</html>