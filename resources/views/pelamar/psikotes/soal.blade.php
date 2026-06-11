@extends('layouts.pelamar')
@section('content')
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-gradient-primary text-white d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center py-3">
                        <div class="mb-2 mb-md-0">
                            <h3 class="card-title mb-1">
                                <i class="fas fa-brain me-2"></i> Tes Psikotes -
                                {{ $sesi->lowongan->judul_lowongan ?? 'N/A' }}
                            </h3>
                            <small class="opacity-75">Jawab dengan jujur dan sesuai kondisi Anda</small>
                        </div>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light btn-sm" id="pause-btn">
                                <i class="fas fa-pause me-1"></i> Pause
                            </button>
                            <button class="btn btn-success btn-sm" id="selesai-tes">
                                <i class="fas fa-check-circle me-1"></i> Selesai
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div
                            class="alert alert-info d-flex justify-content-between align-items-center m-3 mb-2 border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <div class="timer-icon me-3">
                                    <i class="fas fa-clock text-primary fs-4"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">Sisa Waktu</div>
                                    <div id="timer" class="fs-4 fw-bold text-primary"></div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="small text-muted">Soal <span id="current-number">1</span> dari <span
                                        id="total-soal-count">{{ count($sesi->soal_terpilih ?? []) }}</span></div>
                                <div class="small fw-bold" id="soal-status">
                                    <span class="text-warning">○ Belum Dijawab</span>
                                </div>
                            </div>
                        </div>

                        <div class="row m-0">
                            <div class="col-12 col-xl-2 col-lg-3 p-3 border-end bg-light">
                                <div class="question-nav-container">
                                    <div class="card mb-3 border-0 shadow-sm">
                                        <div class="card-header bg-primary text-white border-0 py-2">
                                            <h6 class="mb-0 text-center fw-bold"><i class="fas fa-list me-1"></i> Daftar
                                                Soal</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="question-nav-grid" id="soal-navigation">
                                                @php $soalIds = $sesi->soal_terpilih ?? []; @endphp
                                                @foreach ($soalIds as $index => $soalId)
                                                    @php
                                                        $soal = $soalTersedia[$soalId] ?? null;
                                                        if (!$soal) {
                                                            continue;
                                                        }
                                                        $isAnswered = isset($jawabanSudah[$soalId]);
                                                        $isCurrent = $index == $currentSoalIndex;
                                                    @endphp
                                                    <button type="button"
                                                        class="btn soal-nav-btn position-relative {{ $isCurrent ? 'btn-primary active' : ($isAnswered ? 'btn-success' : 'btn-outline-secondary') }}"
                                                        data-soal-index="{{ $index }}"
                                                        data-soal-id="{{ $soalId }}">
                                                        <span class="btn-text">{{ $index + 1 }}</span>
                                                        @if ($isAnswered)
                                                            <i class="fas fa-check check-icon"></i>
                                                        @endif
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-info text-white border-0 py-2">
                                            <h6 class="mb-0 text-center fw-bold"><i class="fas fa-chart-bar me-1"></i>
                                                Statistik</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="stats-grid">
                                                <div class="stat-item">
                                                    <div class="stat-number text-success" id="soal-terjawab">
                                                        {{ count($jawabanSudah) }}</div>
                                                    <div class="stat-label">Selesai</div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-number text-warning" id="soal-belum">
                                                        {{ count($sesi->soal_terpilih ?? []) - count($jawabanSudah) }}</div>
                                                    <div class="stat-label">Tersisa</div>
                                                </div>
                                                <div class="stat-item">
                                                    <div class="stat-number text-secondary">
                                                        {{ count($sesi->soal_terpilih ?? []) }}</div>
                                                    <div class="stat-label">Total</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xl-10 col-lg-9 p-3">
                                <form id="psikotes-form">
                                    @csrf
                                    <input type="hidden" name="sesi_token" value="{{ $sesi->token_sesi }}">
                                    <input type="hidden" id="current_soal_index" name="current_soal_index"
                                        value="{{ $currentSoalIndex }}">

                                    @foreach ($soalIds as $index => $soalId)
                                        @php
                                            $soal = $soalTersedia[$soalId] ?? null;
                                            if (!$soal) {
                                                continue;
                                            }
                                            $jawabanSudahSoal = $jawabanSudah[$soalId] ?? '';
                                        @endphp
                                        <div class="soal-item {{ $index == $currentSoalIndex ? '' : 'd-none' }}"
                                            data-soal-id="{{ $soalId }}" data-soal-index="{{ $index }}">
                                            <div class="card mb-4 border-0 shadow-sm">
                                                <div class="card-header bg-light border-0 py-3">
                                                    <h4 class="mb-2 fw-bold text-primary">
                                                        <span class="badge bg-primary me-2 fs-6">{{ $index + 1 }}</span>
                                                        Soal {{ $index + 1 }} dari {{ count($soalIds) }}
                                                    </h4>
                                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                                        <span
                                                            class="badge bg-{{ $soal->jenis == 'kognitif' ? 'info' : ($soal->jenis == 'kepribadian' ? 'warning' : 'success') }} px-3 py-2">
                                                            {{ strtoupper($soal->jenis) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-4">
                                                    <div class="question-content mb-4">
                                                        <div
                                                            class="question-text p-4 bg-light rounded-3 border-start border-4 border-primary">
                                                            <h5 class="fw-bold mb-0 text-dark lh-base">
                                                                {!! nl2br(e($soal->pertanyaan)) !!}</h5>
                                                        </div>
                                                    </div>

                                                    @php $opsiJawaban = is_string($soal->opsi_jawaban) ? json_decode($soal->opsi_jawaban, true) ?? [] : $soal->opsi_jawaban ?? []; @endphp
                                                    <div class="answer-options">
                                                        @foreach ($opsiJawaban as $huruf => $opsi)
                                                            <div class="answer-option position-relative rounded-3 mb-3"
                                                                data-option="{{ $huruf }}">
                                                                <input class="form-check-input jawaban-radio" type="radio"
                                                                    name="jawaban_{{ $soalId }}"
                                                                    id="jawaban_{{ $soalId }}_{{ $huruf }}"
                                                                    value="{{ $huruf }}"
                                                                    data-soal-id="{{ $soalId }}"
                                                                    {{ $jawabanSudahSoal == $huruf ? 'checked' : '' }}>
                                                                <label class="option-label w-100 cursor-pointer p-3"
                                                                    for="jawaban_{{ $soalId }}_{{ $huruf }}">
                                                                    <div class="d-flex align-items-center">
                                                                        <span
                                                                            class="option-letter bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold fs-6">{{ $huruf }}</span>
                                                                        <span
                                                                            class="option-text fs-6">{{ $opsi }}</span>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                        <button type="button" class="btn btn-outline-secondary btn-lg px-4"
                                            id="prev-soal"><i class="fas fa-arrow-left me-2"></i>Sebelumnya</button>
                                        <div class="text-center"><span
                                                class="badge bg-light text-dark px-3 py-2 fs-6">Soal <span
                                                    id="current-number-bottom">1</span> dari <span
                                                    id="total-soal-count-bottom">{{ count($sesi->soal_terpilih ?? []) }}</span></span>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-lg px-4"
                                            id="next-soal">Selanjutnya <i class="fas fa-arrow-right ms-2"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .bg-gradient-primary {
                background: linear-gradient(135deg, #007bff, #0056b3);
            }

            .question-nav-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }

            .soal-nav-btn {
                width: 100% !important;
                height: 50px !important;
                font-weight: bold;
                border-radius: 8px;
                position: relative;
            }

            .soal-nav-btn .check-icon {
                position: absolute;
                top: -5px;
                right: -5px;
                background: #fff;
                color: #28a745;
                border-radius: 50%;
                width: 18px;
                height: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 0.7rem;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                text-align: center;
            }

            .stat-item {
                padding: 10px 5px;
                border-radius: 8px;
                background: #f8f9fa;
            }

            .stat-number {
                font-size: 1.3rem;
                font-weight: bold;
            }

            .stat-label {
                font-size: 0.75rem;
                color: #6c757d;
            }

            .answer-option {
                background: #fff;
                border: 2px solid #e9ecef;
                cursor: pointer;
                border-radius: 8px;
                transition: all 0.2s ease;
                margin-bottom: 12px;
            }

            .answer-option:hover {
                border-color: #007bff;
            }

            .answer-option input[type="radio"] {
                position: absolute;
                opacity: 0;
            }

            .answer-option input[type="radio"]:checked+.option-label {
                background: rgba(0, 123, 255, 0.05);
                border-color: #007bff;
            }

            .answer-option input[type="radio"]:checked+.option-label .option-letter {
                background: #007bff;
                transform: scale(1.1);
            }

            .option-label {
                display: block;
                border-radius: 6px;
                padding: 16px;
                cursor: pointer;
            }

            .option-letter {
                width: 35px;
                height: 35px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 12px;
                border-radius: 50%;
                font-weight: bold;
                background-color: #007bff;
                color: white;
                flex-shrink: 0;
            }

            .question-text {
                background: #f8f9fa;
                border-left: 4px solid #007bff !important;
            }

            .timer-icon {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 55px;
                height: 55px;
                background: rgba(0, 123, 255, 0.1);
                border-radius: 50%;
            }

            #timer.text-danger {
                animation: pulse 1.5s infinite;
            }

            @keyframes pulse {

                0%,
                100% {
                    opacity: 1;
                }

                50% {
                    opacity: 0.7;
                }
            }

            .cursor-pointer {
                cursor: pointer;
            }

            #prev-soal,
            #next-soal {
                min-width: 140px;
                font-weight: 600;
            }

            @media (max-width: 768px) {
                .question-nav-grid {
                    grid-template-columns: repeat(5, 1fr);
                }

                .soal-nav-btn {
                    height: 42px !important;
                    font-size: 0.85rem;
                }
            }

            @media (max-width: 576px) {
                .question-nav-grid {
                    grid-template-columns: repeat(4, 1fr);
                }

                .soal-nav-btn {
                    height: 40px !important;
                    font-size: 0.8rem;
                }
            }

            .swal2-popup {
                font-size: 14px !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var totalSoal = parseInt('{{ count($sesi->soal_terpilih ?? []) }}');
                var currentSoalIndex = parseInt('{{ $currentSoalIndex }}');
                var sisaWaktuDetik = parseInt('{{ intval($sisaWaktuDetik) }}');
                var timerInterval = null;
                var isSubmitting = false;
                var tokenSesi = '{{ $sesi->token_sesi }}';
                var csrfToken = '{{ csrf_token() }}';
                var submitUrl = '{{ route('pelamar.psikotes.submit') }}';
                var pauseUrl = '{{ route('pelamar.psikotes.pause') }}';
                var jawabUrl = '{{ route('pelamar.psikotes.jawab') }}';
                var dashboardUrl = '{{ route('pelamar.dashboard') }}';

                var timerElement = document.getElementById('timer');
                var prevBtn = document.getElementById('prev-soal');
                var nextBtn = document.getElementById('next-soal');
                var pauseBtn = document.getElementById('pause-btn');
                var selesaiBtn = document.getElementById('selesai-tes');
                var soalItems = document.querySelectorAll('.soal-item');
                var navButtons = document.querySelectorAll('.soal-nav-btn');
                var currentNumberSpan = document.getElementById('current-number');
                var currentNumberBottomSpan = document.getElementById('current-number-bottom');
                var soalStatusSpan = document.getElementById('soal-status');
                var soalTerjawabSpan = document.getElementById('soal-terjawab');
                var soalBelumSpan = document.getElementById('soal-belum');

                function formatTime(seconds) {
                    if (isNaN(seconds) || seconds < 0) seconds = 0;
                    var mins = Math.floor(seconds / 60);
                    var secs = seconds % 60;
                    return mins.toString().padStart(2, '0') + ':' + secs.toString().padStart(2, '0');
                }

                function updateTimerDisplay() {
                    if (timerElement) {
                        timerElement.textContent = formatTime(sisaWaktuDetik);
                        if (sisaWaktuDetik < 300) {
                            timerElement.classList.add('text-danger');
                        } else {
                            timerElement.classList.remove('text-danger');
                        }
                    }
                }

                function startTimer() {
                    if (timerInterval) clearInterval(timerInterval);
                    timerInterval = setInterval(function() {
                        if (sisaWaktuDetik <= 1) {
                            clearInterval(timerInterval);
                            handleTimeExpired();
                            return;
                        }
                        sisaWaktuDetik--;
                        updateTimerDisplay();
                    }, 1000);
                }

                function updateProgress() {
                    var answered = document.querySelectorAll('.jawaban-radio:checked').length;
                    if (soalTerjawabSpan) soalTerjawabSpan.textContent = answered;
                    if (soalBelumSpan) soalBelumSpan.textContent = totalSoal - answered;

                    for (var i = 0; i < navButtons.length; i++) {
                        var btn = navButtons[i];
                        var soalItem = soalItems[i];
                        if (!soalItem) continue;
                        var soalId = soalItem.dataset.soalId;
                        var isAnswered = document.querySelector('input[name="jawaban_' + soalId + '"]:checked') !==
                        null;
                        var isCurrent = i === currentSoalIndex;
                        btn.classList.remove('btn-success', 'btn-outline-secondary', 'btn-primary', 'active');
                        if (isCurrent) {
                            btn.classList.add('btn-primary', 'active');
                        } else if (isAnswered) {
                            btn.classList.add('btn-success');
                            if (!btn.querySelector('.check-icon')) {
                                var icon = document.createElement('i');
                                icon.className = 'fas fa-check check-icon';
                                btn.appendChild(icon);
                            }
                        } else {
                            btn.classList.add('btn-outline-secondary');
                            var icon = btn.querySelector('.check-icon');
                            if (icon) icon.remove();
                        }
                    }
                }

                function updateCurrentNumber() {
                    if (currentNumberSpan) currentNumberSpan.textContent = currentSoalIndex + 1;
                    if (currentNumberBottomSpan) currentNumberBottomSpan.textContent = currentSoalIndex + 1;
                    var currentSoalItem = soalItems[currentSoalIndex];
                    if (currentSoalItem && soalStatusSpan) {
                        var soalId = currentSoalItem.dataset.soalId;
                        var isAnswered = document.querySelector('input[name="jawaban_' + soalId + '"]:checked') !==
                        null;
                        if (isAnswered) {
                            soalStatusSpan.innerHTML = '<span class="text-success">✓ Sudah Dijawab</span>';
                        } else {
                            soalStatusSpan.innerHTML = '<span class="text-warning">○ Belum Dijawab</span>';
                        }
                    }
                }

                function navigateToSoal(index) {
                    if (index < 0 || index >= totalSoal || isSubmitting) return;
                    if (soalItems[currentSoalIndex]) {
                        soalItems[currentSoalIndex].classList.add('d-none');
                    }
                    currentSoalIndex = index;
                    if (soalItems[currentSoalIndex]) {
                        soalItems[currentSoalIndex].classList.remove('d-none');
                    }
                    var currentSoalIndexInput = document.getElementById('current_soal_index');
                    if (currentSoalIndexInput) currentSoalIndexInput.value = currentSoalIndex;
                    updateCurrentNumber();
                    if (prevBtn) prevBtn.disabled = (currentSoalIndex === 0);
                    if (nextBtn) nextBtn.disabled = (currentSoalIndex === totalSoal - 1);
                    updateProgress();
                }

                function saveCurrentState() {
                    return fetch(pauseUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            sesi_token: tokenSesi,
                            current_soal_index: currentSoalIndex
                        })
                    }).catch(function(err) {
                        console.error('Save error:', err);
                    });
                }

                function simpanJawaban(soalId, jawaban) {
                    return fetch(jawabUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            soal_id: soalId,
                            jawaban: jawaban,
                            sesi_token: tokenSesi
                        })
                    }).then(function(res) {
                        return res.json();
                    }).then(function(data) {
                        if (data.success) {
                            updateProgress();
                            updateCurrentNumber();
                        }
                        return data;
                    }).catch(function(err) {
                        console.error('Save answer error:', err);
                    });
                }

                var radioButtons = document.querySelectorAll('.jawaban-radio');
                for (var i = 0; i < radioButtons.length; i++) {
                    radioButtons[i].addEventListener('change', function() {
                        if (isSubmitting) return;
                        var soalId = this.dataset.soalId;
                        var jawaban = this.value;
                        simpanJawaban(soalId, jawaban);
                    });
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        navigateToSoal(currentSoalIndex - 1);
                    });
                }
                if (nextBtn) {
                    nextBtn.addEventListener('click', function() {
                        navigateToSoal(currentSoalIndex + 1);
                    });
                }

                for (var i = 0; i < navButtons.length; i++) {
                    navButtons[i].addEventListener('click', function() {
                        var index = parseInt(this.dataset.soalIndex);
                        navigateToSoal(index);
                    });
                }

                if (pauseBtn) {
                    pauseBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Apakah Anda ingin menjeda tes?',
                            html: '<div class="text-start">' +
                                '<p><i class="fas fa-info-circle text-primary me-2"></i> <strong>Progress Anda akan tersimpan</strong></p>' +
                                '<p><i class="fas fa-save text-success me-2"></i> Anda dapat melanjutkan kapan saja</p>' +
                                '<p><i class="fas fa-clock text-warning me-2"></i> Waktu akan berhenti saat dijeda</p>' +
                                '</div>',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '<i class="fas fa-pause me-2"></i>Ya, Jeda Tes',
                            cancelButtonText: '<i class="fas fa-times me-2"></i>Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                Swal.fire({
                                    title: 'Menyimpan Progress...',
                                    html: '<div class="spinner-border text-primary" role="status"></div>',
                                    showConfirmButton: false,
                                    allowOutsideClick: false
                                });
                                saveCurrentState().then(() => {
                                    window.location.href = dashboardUrl;
                                });
                            }
                        });
                    });
                }

                function submitTes() {
                    if (isSubmitting) return;

                    var answered = document.querySelectorAll('.jawaban-radio:checked').length;
                    var unanswered = totalSoal - answered;

                    var htmlContent = '<div class="text-start">' +
                        '<div class="mb-3 p-3 bg-light rounded">' +
                        '<h6 class="fw-bold mb-2"><i class="fas fa-chart-bar text-primary me-2"></i>Ringkasan Tes</h6>' +
                        '<p><i class="fas fa-check-circle text-success me-2"></i>Soal Terjawab: <strong>' + answered +
                        '</strong> dari <strong>' + totalSoal + '</strong></p>';

                    if (unanswered > 0) {
                        htmlContent +=
                            '<p class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Soal Belum Dijawab: <strong>' +
                            unanswered + '</strong></p>';
                        htmlContent +=
                            '<div class="alert alert-warning mt-2"><i class="fas fa-info-circle me-2"></i><strong>Perhatian!</strong><br>Anda belum menjawab semua soal. Silakan jawab semua soal terlebih dahulu.</div>';
                    } else {
                        htmlContent +=
                            '<div class="alert alert-success mt-2"><i class="fas fa-check-circle me-2"></i><strong>Lengkap!</strong><br>Anda telah menjawab semua soal.</div>';
                    }

                    htmlContent += '</div>' +
                        '<div class="alert alert-danger mt-2">' +
                        '<i class="fas fa-exclamation-triangle me-2"></i>' +
                        '<strong>⚠️ PERHATIAN PENTING ⚠️</strong><br>' +
                        'Setelah tes diselesaikan, Anda <strong>TIDAK DAPAT</strong> mengubah jawaban lagi.' +
                        '</div>' +
                        '</div>';

                    if (unanswered > 0) {
                        Swal.fire({
                            title: '⚠️ Belum Menjawab Semua Soal',
                            html: htmlContent,
                            icon: 'warning',
                            confirmButtonColor: '#d33',
                            confirmButtonText: '<i class="fas fa-times me-2"></i>Kembali ke Tes'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Penyelesaian Tes',
                        html: htmlContent,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#dc3545',
                        confirmButtonText: '<i class="fas fa-check-circle me-2"></i>Ya, Selesaikan Tes!',
                        cancelButtonText: '<i class="fas fa-arrow-left me-2"></i>Kembali ke Tes',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            isSubmitting = true;
                            selesaiBtn.disabled = true;
                            pauseBtn.disabled = true;
                            selesaiBtn.innerHTML =
                                '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';

                            Swal.fire({
                                title: 'Sedang Memproses...',
                                html: '<div class="spinner-border text-primary" role="status"></div><p class="mt-2">Menyimpan jawaban dan menghitung hasil...</p>',
                                showConfirmButton: false,
                                allowOutsideClick: false
                            });

                            saveCurrentState();

                            fetch(submitUrl, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    sesi_token: tokenSesi
                                })
                            }).then(function(response) {
                                return response.json();
                            }).then(function(data) {
                                if (data.success) {
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        html: '<p class="text-success"><i class="fas fa-check-circle fa-2x"></i></p><p>Tes berhasil diselesaikan.</p>',
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(function() {
                                        window.location.href = data.redirect;
                                    });
                                } else {
                                    throw new Error(data.error || 'Submit gagal');
                                }
                            }).catch(function(err) {
                                console.error('Submit error:', err);
                                isSubmitting = false;
                                selesaiBtn.disabled = false;
                                pauseBtn.disabled = false;
                                selesaiBtn.innerHTML =
                                    '<i class="fas fa-check-circle me-1"></i> Selesai';
                                Swal.fire({
                                    title: 'Gagal!',
                                    html: '<p class="text-danger"><i class="fas fa-times-circle fa-2x"></i></p><p>' +
                                        err.message +
                                        '</p><p>Silakan coba lagi atau hubungi admin.</p>',
                                    icon: 'error',
                                    confirmButtonText: '<i class="fas fa-redo me-2"></i>Coba Lagi'
                                });
                            });
                        }
                    });
                }

                if (selesaiBtn) {
                    selesaiBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        submitTes();
                    });
                }

                function handleTimeExpired() {
                    if (timerInterval) clearInterval(timerInterval);
                    if (prevBtn) prevBtn.disabled = true;
                    if (nextBtn) nextBtn.disabled = true;
                    if (pauseBtn) pauseBtn.disabled = true;
                    if (selesaiBtn) selesaiBtn.disabled = true;
                    for (var i = 0; i < navButtons.length; i++) {
                        navButtons[i].disabled = true;
                    }
                    var allRadios = document.querySelectorAll('.jawaban-radio');
                    for (var i = 0; i < allRadios.length; i++) {
                        allRadios[i].disabled = true;
                    }

                    Swal.fire({
                        title: '⏰ Waktu Telah Habis!',
                        html: '<p>Sistem akan menyimpan jawaban Anda dan mengarahkan ke halaman hasil.</p>',
                        icon: 'warning',
                        showConfirmButton: false,
                        timer: 2000,
                        allowOutsideClick: false
                    });

                    fetch(submitUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            sesi_token: tokenSesi
                        })
                    }).then(function(response) {
                        return response.json();
                    }).then(function(data) {
                        if (data.success) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.href = dashboardUrl;
                        }
                    }).catch(function() {
                        window.location.href = dashboardUrl;
                    });
                }

                if (sisaWaktuDetik > 0 && sisaWaktuDetik < 100000) {
                    startTimer();
                } else {
                    sisaWaktuDetik = {{ intval($sisaWaktuDetik) }};
                    if (sisaWaktuDetik > 0) startTimer();
                    else handleTimeExpired();
                }

                updateTimerDisplay();
                updateProgress();
                updateCurrentNumber();
                if (prevBtn) prevBtn.disabled = (currentSoalIndex === 0);
                if (nextBtn) nextBtn.disabled = (currentSoalIndex === totalSoal - 1);

                window.addEventListener('beforeunload', function() {
                    if (!isSubmitting) saveCurrentState();
                });
            });
        </script>
    @endpush
@endsection
