<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PelamarController;
use App\Http\Controllers\LowonganController;
use App\Http\Controllers\TahapanRekrutmenController;
use App\Http\Controllers\PelamarTahapanController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\SoalPsikotesController;
use App\Http\Controllers\JawabanPsikotesController;
use App\Http\Controllers\HasilPsikotesController;
use App\Http\Controllers\KategoriPsikotesController;
use App\Http\Controllers\KonfigurasiPsikotesController;
use App\Http\Controllers\SesiPsikotesController;
use App\Http\Controllers\AktivitasPsikotesController;
use App\Http\Controllers\AdminPelamarProfilController;
use App\Http\Controllers\MCUController;
use App\Http\Controllers\WawancaraController;
use App\Http\Controllers\SpkRecruitmentController;
use App\Http\Controllers\OfferingController;
use App\Http\Controllers\HiringOnboardingController;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    if (!$user) {
        return redirect()->route('login');
    }

    if (strtolower($user->role) === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (strtolower($user->role) === 'pelamar') {
        return redirect()->route('pelamar.dashboard');
    }

    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('/password', [ProfileController::class, 'password'])->name('password.update');
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'is_admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Pelamar Management
    Route::post('/pelamar/import', [PelamarController::class, 'import'])->name('pelamar.import');
    Route::get('/pelamar/export', [PelamarController::class, 'export'])->name('pelamar.export');
    Route::get('/pelamar/template', [PelamarController::class, 'downloadTemplate'])->name('pelamar.template');
    Route::resource('pelamar', PelamarController::class);
    Route::get('/pelamar/{id}/profil', [AdminPelamarProfilController::class, 'show'])->name('pelamar.profil');

    // Lowongan Management
    Route::resource('lowongan', LowonganController::class);
    Route::get('/lowongan/{lowongan}/applicants', [LowonganController::class, 'showApplicants'])->name('lowongan.applicants');
    Route::get('/lowongan/{lowongan}/applicants/{pelamar}/tahapan', [LowonganController::class, 'showApplicantTahapan'])->name('lowongan.applicant.tahapan');
    Route::put('/lowongan/{lowongan}/applicants/{pelamar}/tahapan/update', [LowonganController::class, 'updateTahapanStatus'])->name('lowongan.applicant.tahapan.update');

    // Tahapan Rekrutmen
    Route::resource('tahapanrekrutmen', TahapanRekrutmenController::class)
        ->parameters(['tahapanrekrutmen' => 'tahapanrekrutmen']);

    // Pelamar Tahapan
    Route::resource('pelamartahapan', PelamarTahapanController::class)->except(['create', 'store', 'edit']);

    // Berkas Management
    Route::resource('berkas', BerkasController::class)
        ->parameters(['berkas' => 'berkas']);

    // Psikotes Management
    Route::resource('kategoripsikotes', KategoriPsikotesController::class);

    // Konfigurasi Psikotes
    Route::resource('konfigurasipsikotes', KonfigurasiPsikotesController::class);
    Route::get('/konfigurasipsikotes/get-durasi/{lowonganId}', [KonfigurasiPsikotesController::class, 'getDurasi'])->name('konfigurasipsikotes.get-durasi');

    // Sesi Psikotes
    Route::resource('sesipsikotes', SesiPsikotesController::class);
    Route::get('/sesipsikotes/get-pelamar/{lowonganId}', [SesiPsikotesController::class, 'getPelamarByLowongan'])->name('sesipsikotes.get-pelamar');
    Route::get('/sesipsikotes/info-lowongan/{lowonganId}', [SesiPsikotesController::class, 'getInfoLowongan'])->name('sesipsikotes.info-lowongan');
    Route::post('/sesipsikotes/debug', [SesiPsikotesController::class, 'debug'])->name('sesipsikotes.debug');

    // Soal Psikotes
    Route::resource('soalpsikotes', SoalPsikotesController::class);
    Route::get('/soalpsikotes/template', [SoalPsikotesController::class, 'downloadTemplate'])->name('soalpsikotes.download-template');
    Route::post('/soalpsikotes/import', [SoalPsikotesController::class, 'import'])->name('soalpsikotes.import');

    // Jawaban & Hasil Psikotes
    Route::resource('jawabanpsikotes', JawabanPsikotesController::class);
    Route::resource('hasilpsikotes', HasilPsikotesController::class);
    Route::resource('aktivitaspsikotes', AktivitasPsikotesController::class);
    Route::post('/hasilpsikotes/calculate/{pelamar}/{lowongan}', [HasilPsikotesController::class, 'calculateResults'])->name('hasilpsikotes.calculate');

    //MCU
    Route::resource('mcu', MCUController::class);
    Route::get('mcu/{id}/input_hasil', [MCUController::class, 'inputHasil'])->name('mcu.input_hasil');
    Route::post('mcu/{id}/store_hasil', [MCUController::class, 'storeHasil'])->name('mcu.store_hasil');
    Route::post('mcu/{id}/verifikasi', [MCUController::class, 'verifikasi'])->name('mcu.verifikasi');
    Route::get('mcu/{id}/generate_surat_hasil', [MCUController::class, 'generateSuratHasil'])->name('mcu.surat_hasil');
    Route::get('mcu/{id}/generate_surat_panggilan', [MCUController::class, 'generateSuratPanggilan'])->name('mcu.surat_panggilan');
    Route::get('mcu/{id}/cetak_surat_panggilan', [MCUController::class, 'cetakSuratPanggilan'])->name('mcu.cetak_surat_panggilan');
    Route::get('mcu/{id}/api_skor', [MCUController::class, 'getFormattedSkor'])->name('mcu.api_skor');
    //Wawancara
    Route::resource('wawancara', WawancaraController::class);
    Route::put('/wawancara/{id}/nilai', [WawancaraController::class, 'nilai'])->name('wawancara.nilai');
    Route::post('/wawancara/{id}/upload-hasil', [WawancaraController::class, 'uploadHasil'])->name('wawancara.upload-hasil');
    Route::put('/wawancara/{id}/verifikasi', [WawancaraController::class, 'verifikasi'])->name('wawancara.verifikasi');

    // SPK Routes
    Route::prefix('spk')->name('spk.')->group(function () {
        Route::get('/', [SpkRecruitmentController::class, 'index'])->name('index');
        Route::get('/kriteria', [SpkRecruitmentController::class, 'kriteria'])->name('kriteria');
        Route::post('/kriteria', [SpkRecruitmentController::class, 'storeKriteria'])->name('kriteria.store');
        Route::put('/kriteria/{id}', [SpkRecruitmentController::class, 'updateKriteria'])->name('kriteria.update');
        Route::delete('/kriteria/{id}', [SpkRecruitmentController::class, 'deleteKriteria'])->name('kriteria.delete');

        Route::get('/penilaian/{lowonganId}', [SpkRecruitmentController::class, 'penilaian'])->name('penilaian');
        Route::post('/penilaian/{lowonganId}', [SpkRecruitmentController::class, 'storePenilaian'])->name('penilaian.store');
        Route::get('/export-penilaian/{lowonganId}', [SpkRecruitmentController::class, 'exportPenilaian'])->name('export-penilaian');

        Route::get('/sinkronisasi/{lowonganId}', [SpkRecruitmentController::class, 'sinkronisasiNilai'])->name('sinkronisasi');

        Route::get('/hitung-saw/{lowonganId}', [SpkRecruitmentController::class, 'hitungSAW'])->name('hitung-saw');
        Route::get('/hitung-topsis/{lowonganId}', [SpkRecruitmentController::class, 'hitungTOPSIS'])->name('hitung-topsis');

        Route::get('/hasil/{lowonganId}', [SpkRecruitmentController::class, 'hasil'])->name('hasil');
        Route::get('/detail-saw/{lowonganId}/{pelamarId}', [SpkRecruitmentController::class, 'detailSAW'])->name('detail-saw');
        Route::get('/detail-topsis/{lowonganId}/{pelamarId}', [SpkRecruitmentController::class, 'detailTOPSIS'])->name('detail-topsis');

        Route::post('/approve/{lowonganId}/{pelamarId}', [SpkRecruitmentController::class, 'approve'])->name('approve');
        Route::get('/offering/{lowonganId}', [SpkRecruitmentController::class, 'offering'])->name('offering');
        Route::get('/export/{lowonganId}/{metode}', [SpkRecruitmentController::class, 'exportExcel'])->name('export');
    });

    // Offering Routes
    Route::prefix('offering')->name('offering.')->group(function () {
        Route::get('/dashboard-stats', [OfferingController::class, 'dashboardStats'])->name('dashboard-stats');
        Route::get('/{lowonganId}', [OfferingController::class, 'adminIndex'])->name('index');
        Route::get('/{lowonganId}/create/{pelamarId}', [OfferingController::class, 'adminCreate'])->name('create');
        Route::post('/{lowonganId}/store/{pelamarId}', [OfferingController::class, 'adminStore'])->name('store');
        Route::post('/{lowonganId}/approve/{offeringId}', [OfferingController::class, 'adminApprove'])->name('approve');
        Route::post('/{lowonganId}/reject-approval/{offeringId}', [OfferingController::class, 'adminRejectApproval'])->name('reject-approval');
        Route::post('/{lowonganId}/resend/{offeringId}', [OfferingController::class, 'adminResend'])->name('resend');
        Route::delete('/{lowonganId}/cancel/{offeringId}', [OfferingController::class, 'adminCancel'])->name('cancel');
        Route::get('/{lowonganId}/show/{offeringId}', [OfferingController::class, 'adminShow'])->name('show');
    });

    // Hiring Routes
    Route::prefix('hiring')->name('hiring.')->group(function () {
        Route::get('/{lowonganId}', [HiringOnboardingController::class, 'adminHiringIndex'])->name('index');
        Route::get('/{lowonganId}/create/{offeringId}', [HiringOnboardingController::class, 'adminHiringCreate'])->name('create');
        Route::post('/{lowonganId}/store/{offeringId}', [HiringOnboardingController::class, 'adminHiringStore'])->name('store');
        Route::get('/{lowonganId}/show/{hiringId}', [HiringOnboardingController::class, 'adminHiringShow'])->name('show');
        Route::post('/{lowonganId}/probation/{hiringId}', [HiringOnboardingController::class, 'adminProbationComplete'])->name('probation');
    });

    // Onboarding Routes
    Route::prefix('onboarding')->name('onboarding.')->group(function () {
        Route::get('/{lowonganId}', [HiringOnboardingController::class, 'adminOnboardingIndex'])->name('index');
        Route::get('/{lowonganId}/create/{hiringId}', [HiringOnboardingController::class, 'adminOnboardingCreate'])->name('create');
        Route::post('/{lowonganId}/store/{hiringId}', [HiringOnboardingController::class, 'adminOnboardingStore'])->name('store');
        Route::get('/{lowonganId}/show/{onboardingId}', [HiringOnboardingController::class, 'adminOnboardingShow'])->name('show');
    });
});

// Pelamar Routes
Route::prefix('pelamar')->middleware(['auth', 'is_pelamar'])->name('pelamar.')->group(function () {
    Route::get('/dashboard', function () {
        return view('pelamar.dashboard');
    })->name('dashboard');

    Route::get('/dashboard-stats', [PelamarController::class, 'dashboardStats'])->name('dashboard-stats');

    // Profil Lengkap
    Route::get('/profil', [App\Http\Controllers\PelamarProfilController::class, 'index'])->name('profil.index');
    Route::put('/profil/update', [App\Http\Controllers\PelamarProfilController::class, 'updateProfil'])->name('profil.update');

    // Pendidikan
    Route::post('/profil/pendidikan', [App\Http\Controllers\PelamarProfilController::class, 'storePendidikan'])->name('profil.pendidikan.store');
    Route::put('/profil/pendidikan/{id}', [App\Http\Controllers\PelamarProfilController::class, 'updatePendidikan'])->name('profil.pendidikan.update');
    Route::delete('/profil/pendidikan/{id}', [App\Http\Controllers\PelamarProfilController::class, 'destroyPendidikan'])->name('profil.pendidikan.destroy');

    // Organisasi
    Route::post('/profil/organisasi', [App\Http\Controllers\PelamarProfilController::class, 'storeOrganisasi'])->name('profil.organisasi.store');
    Route::put('/profil/organisasi/{id}', [App\Http\Controllers\PelamarProfilController::class, 'updateOrganisasi'])->name('profil.organisasi.update');
    Route::delete('/profil/organisasi/{id}', [App\Http\Controllers\PelamarProfilController::class, 'destroyOrganisasi'])->name('profil.organisasi.destroy');

    // Magang
    Route::post('/profil/magang', [App\Http\Controllers\PelamarProfilController::class, 'storeMagang'])->name('profil.magang.store');
    Route::put('/profil/magang/{id}', [App\Http\Controllers\PelamarProfilController::class, 'updateMagang'])->name('profil.magang.update');
    Route::delete('/profil/magang/{id}', [App\Http\Controllers\PelamarProfilController::class, 'destroyMagang'])->name('profil.magang.destroy');

    // Pengalaman Kerja
    Route::post('/profil/pengalaman', [App\Http\Controllers\PelamarProfilController::class, 'storePengalaman'])->name('profil.pengalaman.store');
    Route::put('/profil/pengalaman/{id}', [App\Http\Controllers\PelamarProfilController::class, 'updatePengalaman'])->name('profil.pengalaman.update');
    Route::delete('/profil/pengalaman/{id}', [App\Http\Controllers\PelamarProfilController::class, 'destroyPengalaman'])->name('profil.pengalaman.destroy');

    // Bahasa
    Route::post('/profil/bahasa', [App\Http\Controllers\PelamarProfilController::class, 'storeBahasa'])->name('profil.bahasa.store');
    Route::put('/profil/bahasa/{id}', [App\Http\Controllers\PelamarProfilController::class, 'updateBahasa'])->name('profil.bahasa.update');
    Route::delete('/profil/bahasa/{id}', [App\Http\Controllers\PelamarProfilController::class, 'destroyBahasa'])->name('profil.bahasa.destroy');

    // Keahlian
    Route::post('/profil/keahlian', [App\Http\Controllers\PelamarProfilController::class, 'storeKeahlian'])->name('profil.keahlian.store');
    Route::put('/profil/keahlian/{id}', [App\Http\Controllers\PelamarProfilController::class, 'updateKeahlian'])->name('profil.keahlian.update');
    Route::delete('/profil/keahlian/{id}', [App\Http\Controllers\PelamarProfilController::class, 'destroyKeahlian'])->name('profil.keahlian.destroy');

    // Lowongan
    Route::get('/lowongan', [LowonganController::class, 'listLowongan'])->name('lowongan.index');
    Route::get('/lowongan/{lowongan}', [LowonganController::class, 'showLowongan'])->name('lowongan.show');
    Route::post('/lowongan/{lowongan}/apply', [LowonganController::class, 'applyLowongan'])->name('lowongan.apply');

    // Tahapan Pelamar
    Route::get('/tahapan-saya', [App\Http\Controllers\PelamarTahapanController::class, 'pelamarIndex'])->name('pelamartahapan.index');
    Route::get('/tahapan-saya/{id}', [App\Http\Controllers\PelamarTahapanController::class, 'pelamarShow'])->name('pelamartahapan.show');
    Route::post('/tahapan-saya/{id}/upload', [App\Http\Controllers\PelamarTahapanController::class, 'uploadBerkas'])->name('pelamartahapan.upload');

    // Berkas
    Route::get('/berkas', [BerkasController::class, 'index'])->name('berkas.index');
    Route::get('/berkas/create', [BerkasController::class, 'create'])->name('berkas.create');
    Route::post('/berkas', [BerkasController::class, 'store'])->name('berkas.store');
    Route::get('/berkas/{berkas}', [BerkasController::class, 'show'])->name('berkas.show');
    Route::get('/berkas/{berkas}/edit', [BerkasController::class, 'edit'])->name('berkas.edit');
    Route::put('/berkas/{berkas}', [BerkasController::class, 'update'])->name('berkas.update');

    // Psikotes Routes
    Route::prefix('psikotes')->name('psikotes.')->group(function () {
        Route::get('/token', [SesiPsikotesController::class, 'showTokenForm'])->name('token');
        Route::post('/start', [SesiPsikotesController::class, 'startByToken'])->name('start');
        Route::get('/soal/{token}', [SesiPsikotesController::class, 'showSoalPsikotes'])->name('soal');
        Route::get('/hasil/{token}', [HasilPsikotesController::class, 'showHasilPelamar'])->name('hasil');
        Route::get('/hasil/detail/{token}', [HasilPsikotesController::class, 'showHasilPelamar'])->name('hasil.detail');
        Route::post('/jawab', [SesiPsikotesController::class, 'simpanJawaban'])->name('jawab');
        Route::post('/pause', [SesiPsikotesController::class, 'pauseTes'])->name('pause');
        Route::post('/resume', [SesiPsikotesController::class, 'resumeTes'])->name('resume');
        Route::post('/submit', [SesiPsikotesController::class, 'submitTes'])->name('submit');
        Route::get('/hasilpsikotes', [HasilPsikotesController::class, 'showForPelamar'])->name('hasilpsikotes.index');
        Route::get('/hasilpsikotes/{hasilpsikotes}', [HasilPsikotesController::class, 'show'])->name('hasilpsikotes.show');
    });

    Route::get('/psikotes', [SoalPsikotesController::class, 'showForPelamar'])->name('psikotes.index');

    //MCU
    Route::get('mcu', [MCUController::class, 'index'])->name('mcu.index');
    Route::get('mcu/{id}', [MCUController::class, 'pelamarShow'])->name('mcu.show');
    Route::get('mcu/{id}/download_surat', [MCUController::class, 'downloadSurat'])->name('mcu.download_surat');
    Route::get('mcu/{id}/download_surat_hasil', [MCUController::class, 'downloadSuratHasil'])->name('mcu.download_surat_hasil');
    Route::post('mcu/{id}/upload_dokumen', [MCUController::class, 'uploadDokumen'])->name('mcu.upload_dokumen');
    Route::post('mcu/{id}/konfirmasi', [MCUController::class, 'konfirmasi'])->name('mcu.konfirmasi');

    //Wawancara
    Route::get('/wawancara', [WawancaraController::class, 'index'])->name('wawancara.index');
    Route::get('/wawancara/{id}', [WawancaraController::class, 'show'])->name('wawancara.show');
    Route::post('/wawancara/{id}/upload-dokumen', [WawancaraController::class, 'uploadDokumen'])->name('wawancara.upload-dokumen');
    Route::post('/wawancara/{id}/konfirmasi', [WawancaraController::class, 'konfirmasi'])->name('wawancara.konfirmasi');

    // SPK
    Route::get('/spk-hasil', [SpkRecruitmentController::class, 'pelamarHasil'])->name('spk.hasil');

    // Offering Pelamar
    Route::prefix('offering')->name('offering.')->group(function () {
        Route::get('/', [OfferingController::class, 'pelamarIndex'])->name('index');
        Route::get('/{id}', [OfferingController::class, 'pelamarShow'])->name('show');
        Route::post('/{id}/accept', [OfferingController::class, 'pelamarAccept'])->name('accept');
        Route::post('/{id}/reject', [OfferingController::class, 'pelamarReject'])->name('reject');
        Route::post('/{id}/counter', [OfferingController::class, 'pelamarCounter'])->name('counter');
    });

    // Status Seleksi Pelamar
    Route::get('/status-seleksi', [HiringOnboardingController::class, 'pelamarStatus'])->name('status.seleksi');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/file/view/{model}/{id}/{field}', function ($model, $id, $field) {
        $user = Auth::user();
        if (!$user) {
            abort(403, 'Login diperlukan.');
        }

        $modelClass = 'App\\Models\\' . ucfirst($model);
        if (!class_exists($modelClass)) {
            abort(404, 'Model tidak ditemukan.');
        }

        $data = $modelClass::findOrFail($id);

        $isAdmin = $user->isAdmin();
        $isOwner = false;

        if ($user->pelamar) {
            if (isset($data->pelamar_id)) {
                $isOwner = $data->pelamar_id == $user->pelamar->id;
            }
        }

        if (!$isAdmin && !$isOwner) {
            abort(403, 'Akses ditolak.');
        }

        if ($data->$field && Storage::disk('public')->exists($data->$field)) {
            $path = storage_path('app/public/' . $data->$field);
            return response()->file($path);
        }

        abort(404, 'File tidak ditemukan.');
    })->name('file.view');
});

require __DIR__ . '/auth.php';
