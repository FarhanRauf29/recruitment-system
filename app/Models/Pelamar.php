<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Pelamar extends Model
{
    use HasFactory;

    protected $table = 'pelamar';

    protected $fillable = [
        'user_id',
        'nama_depan',
        'nama_belakang',
        'nama_lengkap',
        'email',
        'jenis_kelamin',
        'tempat_lahir',
        'kewarganegaraan',
        'nomor_handphone',
        'gaji_diharapkan',
        'alamat_domisili',
        'provinsi',
        'pendidikan_terakhir',
        'kab_kota',
        'kecamatan',
        'desa_kelurahan',
        'instansi',
        'jurusan',
        'tanggal_lahir',
        'nomor_identitas',
        'nomor_whatsapp',
        'foto_profil',
        'umur',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public static function pendidikanOptions()
    {
        return [
            'SD' => 'SD',
            'SMP' => 'SMP',
            'SMA/SMK' => 'SMA/SMK',
            'Diploma (D3)' => 'Diploma (D3)',
            'Diploma (D4)' => 'Diploma (D4)',
            'Sarjana (S1)' => 'Sarjana (S1)',
            'Magister (S2)' => 'Magister (S2)',
        ];
    }

    public static function jenisKelaminOptions()
    {
        return [
            'Laki-laki' => 'Laki-laki',
            'Perempuan' => 'Perempuan',
        ];
    }

    public static function kewarganegaraanOptions()
    {
        return [
            'WNI' => 'WNI',
            'WNA' => 'WNA',
        ];
    }

    public static function jurusanOptions()
    {
        return [
            'Teknik Informatika' => 'Teknik Informatika',
            'Sistem Informasi' => 'Sistem Informasi',
            'Teknik Komputer' => 'Teknik Komputer',
            'Manajemen Informatika' => 'Manajemen Informatika',
            'Teknologi Informasi' => 'Teknologi Informasi',
            'Ilmu Komputer' => 'Ilmu Komputer',
            'Rekayasa Perangkat Lunak' => 'Rekayasa Perangkat Lunak',
            'Teknologi Komputer' => 'Teknologi Komputer',
            'Sistem Komputer' => 'Sistem Komputer',
            'Teknik Elektro' => 'Teknik Elektro',
            'Teknik Telekomunikasi' => 'Teknik Telekomunikasi',
            'Teknik Biomedis' => 'Teknik Biomedis',
            'Teknik Mekatronika' => 'Teknik Mekatronika',
            'Teknik Robotika' => 'Teknik Robotika',
            'Teknik Jaringan' => 'Teknik Jaringan',
            'Keamanan Siber' => 'Keamanan Siber',
            'Data Sains' => 'Data Sains',
            'Kecerdasan Buatan' => 'Kecerdasan Buatan',
            'Teknik Mesin' => 'Teknik Mesin',
            'Teknik Sipil' => 'Teknik Sipil',
            'Teknik Industri' => 'Teknik Industri',
            'Teknik Kimia' => 'Teknik Kimia',
            'Teknik Lingkungan' => 'Teknik Lingkungan',
            'Teknik Pertanian' => 'Teknik Pertanian',
            'Teknik Perikanan' => 'Teknik Perikanan',
            'Teknik Geologi' => 'Teknik Geologi',
            'Teknik Geodesi' => 'Teknik Geodesi',
            'Teknik Arsitektur' => 'Teknik Arsitektur',
            'Teknik Perencanaan Wilayah' => 'Teknik Perencanaan Wilayah',
            'Teknik Nuklir' => 'Teknik Nuklir',
            'Teknik Fisika' => 'Teknik Fisika',
            'Teknik Material' => 'Teknik Material',
            'Teknik Manufaktur' => 'Teknik Manufaktur',
            'Teknik Otomotif' => 'Teknik Otomotif',
            'Teknik Penerbangan' => 'Teknik Penerbangan',
            'Teknik Perkapalan' => 'Teknik Perkapalan',
            'Manajemen' => 'Manajemen',
            'Akuntansi' => 'Akuntansi',
            'Ekonomi' => 'Ekonomi',
            'Manajemen Keuangan' => 'Manajemen Keuangan',
            'Manajemen Pemasaran' => 'Manajemen Pemasaran',
            'Manajemen Sumber Daya Manusia' => 'Manajemen Sumber Daya Manusia',
            'Manajemen Operasi' => 'Manajemen Operasi',
            'Akuntansi Perpajakan' => 'Akuntansi Perpajakan',
            'Akuntansi Sektor Publik' => 'Akuntansi Sektor Publik',
            'Ekonomi Pembangunan' => 'Ekonomi Pembangunan',
            'Ekonomi Syariah' => 'Ekonomi Syariah',
            'Bisnis Digital' => 'Bisnis Digital',
            'Kewirausahaan' => 'Kewirausahaan',
            'Perbankan' => 'Perbankan',
            'Asuransi' => 'Asuransi',
            'Hukum' => 'Hukum',
            'Ilmu Hukum' => 'Ilmu Hukum',
            'Hukum Bisnis' => 'Hukum Bisnis',
            'Hukum Internasional' => 'Hukum Internasional',
            'Ilmu Sosial' => 'Ilmu Sosial',
            'Ilmu Politik' => 'Ilmu Politik',
            'Hubungan Internasional' => 'Hubungan Internasional',
            'Sosiologi' => 'Sosiologi',
            'Antropologi' => 'Antropologi',
            'Kriminologi' => 'Kriminologi',
            'Administrasi Publik' => 'Administrasi Publik',
            'Administrasi Bisnis' => 'Administrasi Bisnis',
            'Administrasi Negara' => 'Administrasi Negara',
            'Psikologi' => 'Psikologi',
            'Psikologi Industri' => 'Psikologi Industri',
            'Psikologi Pendidikan' => 'Psikologi Pendidikan',
            'Psikologi Klinis' => 'Psikologi Klinis',
            'Pendidikan' => 'Pendidikan',
            'Pendidikan Guru' => 'Pendidikan Guru',
            'Pendidikan Bahasa' => 'Pendidikan Bahasa',
            'Pendidikan Matematika' => 'Pendidikan Matematika',
            'Pendidikan Fisika' => 'Pendidikan Fisika',
            'Pendidikan Kimia' => 'Pendidikan Kimia',
            'Pendidikan Biologi' => 'Pendidikan Biologi',
            'Pendidikan Sejarah' => 'Pendidikan Sejarah',
            'Pendidikan Ekonomi' => 'Pendidikan Ekonomi',
            'Pendidikan Teknik' => 'Pendidikan Teknik',
            'Pendidikan Olahraga' => 'Pendidikan Olahraga',
            'Pendidikan Seni' => 'Pendidikan Seni',
            'Pendidikan Luar Sekolah' => 'Pendidikan Luar Sekolah',
            'Pendidikan Anak Usia Dini' => 'Pendidikan Anak Usia Dini',
            'Kedokteran' => 'Kedokteran',
            'Kedokteran Gigi' => 'Kedokteran Gigi',
            'Keperawatan' => 'Keperawatan',
            'Farmasi' => 'Farmasi',
            'Kesehatan Masyarakat' => 'Kesehatan Masyarakat',
            'Gizi' => 'Gizi',
            'Fisioterapi' => 'Fisioterapi',
            'Okupasi Terapi' => 'Okupasi Terapi',
            'Radiologi' => 'Radiologi',
            'Analis Kesehatan' => 'Analis Kesehatan',
            'Kebidanan' => 'Kebidanan',
            'Keperawatan Anestesi' => 'Keperawatan Anestesi',
            'Keperawatan Jiwa' => 'Keperawatan Jiwa',
            'Keperawatan Komunitas' => 'Keperawatan Komunitas',
            'Desain Komunikasi Visual' => 'Desain Komunikasi Visual',
            'Desain Interior' => 'Desain Interior',
            'Desain Produk' => 'Desain Produk',
            'Desain Grafis' => 'Desain Grafis',
            'Seni Rupa' => 'Seni Rupa',
            'Seni Musik' => 'Seni Musik',
            'Seni Tari' => 'Seni Tari',
            'Seni Teater' => 'Seni Teater',
            'Film dan Televisi' => 'Film dan Televisi',
            'Fotografi' => 'Fotografi',
            'Kriya' => 'Kriya',
            'Animasi' => 'Animasi',
            'Game Design' => 'Game Design',
            'Multimedia' => 'Multimedia',
            'Arsitektur Lansekap' => 'Arsitektur Lansekap',
            'Ilmu Komunikasi' => 'Ilmu Komunikasi',
            'Jurnalistik' => 'Jurnalistik',
            'Public Relations' => 'Public Relations',
            'Periklanan' => 'Periklanan',
            'Broadcasting' => 'Broadcasting',
            'Media Digital' => 'Media Digital',
            'Komunikasi Pemasaran' => 'Komunikasi Pemasaran',
            'Komunikasi Organisasi' => 'Komunikasi Organisasi',
            'Sastra Indonesia' => 'Sastra Indonesia',
            'Sastra Inggris' => 'Sastra Inggris',
            'Sastra Jepang' => 'Sastra Jepang',
            'Sastra Mandarin' => 'Sastra Mandarin',
            'Sastra Arab' => 'Sastra Arab',
            'Sastra Prancis' => 'Sastra Prancis',
            'Sastra Jerman' => 'Sastra Jerman',
            'Bahasa dan Sastra' => 'Bahasa dan Sastra',
            'Linguistik' => 'Linguistik',
            'Terjemahan' => 'Terjemahan',
            'Ilmu Al-Quran' => 'Ilmu Al-Quran',
            'Ilmu Hadis' => 'Ilmu Hadis',
            'Perbandingan Agama' => 'Perbandingan Agama',
            'Filsafat' => 'Filsafat',
            'Teologi' => 'Teologi',
            'Pendidikan Agama' => 'Pendidikan Agama',
            'Manajemen Dakwah' => 'Manajemen Dakwah',
            'Komunikasi Penyiaran Islam' => 'Komunikasi Penyiaran Islam',
            'Ekonomi Islam' => 'Ekonomi Islam',
            'Hukum Islam' => 'Hukum Islam',
            'Lainnya' => 'Lainnya',
        ];
    }

    public static function instansiOptions()
    {
        return [
            'Universitas Indonesia' => 'Universitas Indonesia',
            'Universitas Gadjah Mada' => 'Universitas Gadjah Mada',
            'Institut Teknologi Bandung' => 'Institut Teknologi Bandung',
            'Universitas Padjadjaran' => 'Universitas Padjadjaran',
            'Universitas Diponegoro' => 'Universitas Diponegoro',
            'Universitas Airlangga' => 'Universitas Airlangga',
            'Universitas Brawijaya' => 'Universitas Brawijaya',
            'Universitas Sebelas Maret' => 'Universitas Sebelas Maret',
            'Universitas Negeri Jakarta' => 'Universitas Negeri Jakarta',
            'Universitas Negeri Yogyakarta' => 'Universitas Negeri Yogyakarta',
            'Universitas Negeri Malang' => 'Universitas Negeri Malang',
            'Universitas Negeri Padang' => 'Universitas Negeri Padang',
            'Universitas Negeri Semarang' => 'Universitas Negeri Semarang',
            'Universitas Negeri Surabaya' => 'Universitas Negeri Surabaya',
            'Universitas Negeri Makassar' => 'Universitas Negeri Makassar',
            'Universitas Pendidikan Indonesia' => 'Universitas Pendidikan Indonesia',
            'Institut Pertanian Bogor' => 'Institut Pertanian Bogor',
            'Institut Teknologi Sepuluh Nopember' => 'Institut Teknologi Sepuluh Nopember',
            'Institut Seni Indonesia' => 'Institut Seni Indonesia',
            'Universitas Sumatera Utara' => 'Universitas Sumatera Utara',
            'Universitas Andalas' => 'Universitas Andalas',
            'Universitas Sriwijaya' => 'Universitas Sriwijaya',
            'Universitas Hasanuddin' => 'Universitas Hasanuddin',
            'Universitas Sam Ratulangi' => 'Universitas Sam Ratulangi',
            'Universitas Udayana' => 'Universitas Udayana',
            'Universitas Mataram' => 'Universitas Mataram',
            'Universitas Tanjungpura' => 'Universitas Tanjungpura',
            'Universitas Mulawarman' => 'Universitas Mulawarman',
            'Universitas Pattimura' => 'Universitas Pattimura',
            'Universitas Cenderawasih' => 'Universitas Cenderawasih',
            'Universitas Riau' => 'Universitas Riau',
            'Universitas Jambi' => 'Universitas Jambi',
            'Universitas Bengkulu' => 'Universitas Bengkulu',
            'Universitas Lampung' => 'Universitas Lampung',
            'Universitas Palangka Raya' => 'Universitas Palangka Raya',
            'Universitas Bina Nusantara' => 'Universitas Bina Nusantara',
            'Universitas Trisakti' => 'Universitas Trisakti',
            'Universitas Atma Jaya' => 'Universitas Atma Jaya',
            'Universitas Tarumanagara' => 'Universitas Tarumanagara',
            'Universitas Gunadarma' => 'Universitas Gunadarma',
            'Universitas Mercu Buana' => 'Universitas Mercu Buana',
            'Universitas Esa Unggul' => 'Universitas Esa Unggul',
            'Universitas Pelita Harapan' => 'Universitas Pelita Harapan',
            'Universitas Kristen Krida Wacana' => 'Universitas Kristen Krida Wacana',
            'Universitas Katolik Soegijapranata' => 'Universitas Katolik Soegijapranata',
            'Universitas Katolik Parahyangan' => 'Universitas Katolik Parahyangan',
            'Universitas Muhammadiyah Yogyakarta' => 'Universitas Muhammadiyah Yogyakarta',
            'Universitas Muhammadiyah Malang' => 'Universitas Muhammadiyah Malang',
            'Universitas Muhammadiyah Surakarta' => 'Universitas Muhammadiyah Surakarta',
            'Universitas Muhammadiyah Jakarta' => 'Universitas Muhammadiyah Jakarta',
            'Universitas Al Azhar Indonesia' => 'Universitas Al Azhar Indonesia',
            'Universitas Islam Indonesia' => 'Universitas Islam Indonesia',
            'Universitas Islam Negeri Syarif Hidayatullah' => 'Universitas Islam Negeri Syarif Hidayatullah',
            'Universitas Islam Negeri Sunan Gunung Djati' => 'Universitas Islam Negeri Sunan Gunung Djati',
            'Universitas Islam Negeri Sunan Kalijaga' => 'Universitas Islam Negeri Sunan Kalijaga',
            'Universitas Islam Negeri Maulana Malik Ibrahim' => 'Universitas Islam Negeri Maulana Malik Ibrahim',
            'Universitas Islam Negeri Ar-Raniry' => 'Universitas Islam Negeri Ar-Raniry',
            'Universitas Islam Negeri Sumatera Utara' => 'Universitas Islam Negeri Sumatera Utara',
            'Universitas Islam Negeri Raden Fatah' => 'Universitas Islam Negeri Raden Fatah',
            'Universitas Islam Negeri Walisongo' => 'Universitas Islam Negeri Walisongo',
            'Politeknik Negeri Bandung' => 'Politeknik Negeri Bandung',
            'Politeknik Negeri Jakarta' => 'Politeknik Negeri Jakarta',
            'Politeknik Negeri Medan' => 'Politeknik Negeri Medan',
            'Politeknik Negeri Padang' => 'Politeknik Negeri Padang',
            'Politeknik Negeri Malang' => 'Politeknik Negeri Malang',
            'Politeknik Negeri Semarang' => 'Politeknik Negeri Semarang',
            'Politeknik Negeri Surabaya' => 'Politeknik Negeri Surabaya',
            'Politeknik Negeri Manado' => 'Politeknik Negeri Manado',
            'Politeknik Negeri Ujung Pandang' => 'Politeknik Negeri Ujung Pandang',
            'Politeknik Negeri Sriwijaya' => 'Politeknik Negeri Sriwijaya',
            'Politeknik Elektronika Negeri Surabaya' => 'Politeknik Elektronika Negeri Surabaya',
        ];
    }

    public static function bahasaOptions()
    {
        return [
            'Indonesia' => 'Indonesia',
            'Inggris' => 'Inggris',
            'Arab' => 'Arab',
            'Mandarin' => 'Mandarin',
            'Jepang' => 'Jepang',
            'Korea' => 'Korea',
            'Prancis' => 'Prancis',
            'Jerman' => 'Jerman',
            'Belanda' => 'Belanda',
            'Spanyol' => 'Spanyol',
            'Rusia' => 'Rusia',
            'Hindi' => 'Hindi',
            'Melayu' => 'Melayu',
            'Sunda' => 'Sunda',
            'Jawa' => 'Jawa',
            'Bugis' => 'Bugis',
            'Minang' => 'Minang',
            'Lainnya' => 'Lainnya',
        ];
    }

    public static function getTingkatSekolahDasar()
    {
        return ['SD', 'SMP', 'SMA/SMK'];
    }

    public static function getTingkatPerguruanTinggi()
    {
        return ['Diploma (D3)', 'Diploma (D4)', 'Sarjana (S1)', 'Magister (S2)'];
    }

    public static function isTingkatSekolahDasar($tingkat)
    {
        return in_array($tingkat, self::getTingkatSekolahDasar());
    }

    public static function isTingkatPerguruanTinggi($tingkat)
    {
        return in_array($tingkat, self::getTingkatPerguruanTinggi());
    }

    protected static function booted()
    {
        static::creating(function ($pelamar) {
            if ($pelamar->nama_depan && $pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_depan . ' ' . $pelamar->nama_belakang;
            } elseif ($pelamar->nama_depan) {
                $pelamar->nama_lengkap = $pelamar->nama_depan;
            } elseif ($pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_belakang;
            }
            if ($pelamar->tanggal_lahir) {
                $pelamar->umur = Carbon::parse($pelamar->tanggal_lahir)->age;
            }
        });

        static::updating(function ($pelamar) {
            if ($pelamar->nama_depan && $pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_depan . ' ' . $pelamar->nama_belakang;
            } elseif ($pelamar->nama_depan) {
                $pelamar->nama_lengkap = $pelamar->nama_depan;
            } elseif ($pelamar->nama_belakang) {
                $pelamar->nama_lengkap = $pelamar->nama_belakang;
            }
            if ($pelamar->tanggal_lahir) {
                $pelamar->umur = Carbon::parse($pelamar->tanggal_lahir)->age;
            }
        });

        static::deleted(function ($pelamar) {
            if ($pelamar->foto_profil) {
                Storage::disk('public')->delete($pelamar->foto_profil);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lowongans(): BelongsToMany
    {
        return $this->belongsToMany(Lowongan::class, 'lowongan_pelamar')
            ->withPivot(['status', 'created_at', 'updated_at'])
            ->withTimestamps();
    }

    public function pelamarTahapan(): HasMany
    {
        return $this->hasMany(PelamarTahapan::class);
    }

    public function berkas(): HasMany
    {
        return $this->hasMany(Berkas::class);
    }

    public function hasilPsikotes(): HasMany
    {
        return $this->hasMany(HasilPsikotes::class);
    }

    public function jawabanPsikotes(): HasMany
    {
        return $this->hasMany(JawabanPsikotes::class);
    }

    public function riwayatPendidikan(): HasMany
    {
        return $this->hasMany(RiwayatPendidikan::class);
    }

    public function riwayatOrganisasi(): HasMany
    {
        return $this->hasMany(RiwayatOrganisasi::class);
    }

    public function riwayatMagang(): HasMany
    {
        return $this->hasMany(RiwayatMagang::class);
    }

    public function riwayatPengalaman(): HasMany
    {
        return $this->hasMany(RiwayatPengalaman::class);
    }

    public function riwayatBahasa(): HasMany
    {
        return $this->hasMany(RiwayatBahasa::class);
    }

    public function riwayatKeahlian(): HasMany
    {
        return $this->hasMany(RiwayatKeahlian::class);
    }

    public function isRegisteredForLowongan($lowonganId)
    {
        return $this->lowongans()->where('lowongan_id', $lowonganId)->exists();
    }
}
