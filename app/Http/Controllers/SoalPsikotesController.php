<?php

namespace App\Http\Controllers;

use App\Models\SoalPsikotes;
use App\Models\KategoriPsikotes;
use App\Models\SesiPsikotes;
use App\Models\JawabanPsikotes;
use App\Models\PelamarTahapan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use League\Csv\Reader;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Collection;

class SoalPsikotesController extends Controller
{
    public function index(Request $request)
    {
        $kategoris = KategoriPsikotes::all();
        $query = SoalPsikotes::with('kategori')->latest();
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('pertanyaan', 'like', "%$search%")
                    ->orWhere('kode_soal', 'like', "%$search%");
            });
        }
        if ($request->filled('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        $soals = $query->paginate(10);
        return view('admin.soalpsikotes.index', compact('soals', 'kategoris'));
    }
    public function create()
    {
        $kategoris = KategoriPsikotes::all();
        return view('admin.soalpsikotes.create', compact('kategoris'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_psikotes,id',
            'pertanyaan' => 'required|string',
            'jenis' => 'required|in:kognitif,kepribadian,minat',
            'opsi_jawaban_text' => 'required|array|min:2',
            'kunci_jawaban' => 'nullable|string|max:10',
            'bobot_nilai' => 'required|integer|min:1',
            'tingkat_kesulitan' => 'required|integer|min:1|max:5',
            'waktu_per_soal_detik' => 'required|integer|min:10',
            'kode_soal' => 'required|string|unique:soal_psikotes,kode_soal',
            'penjelasan' => 'nullable|string',
            'bobot_opsi' => 'nullable|array',
        ]);
        $opsiJawaban = [];
        foreach ($request->opsi_jawaban_text as $index => $opsi) {
            if (!empty($opsi)) {
                $huruf = chr(65 + $index);
                $opsiJawaban[$huruf] = $opsi;
            }
        }
        SoalPsikotes::create([
            'kategori_id' => $request->kategori_id,
            'pertanyaan' => $request->pertanyaan,
            'jenis' => $request->jenis,
            'opsi_jawaban' => $opsiJawaban,
            'kunci_jawaban' => $request->kunci_jawaban,
            'bobot_opsi' => $request->bobot_opsi,
            'bobot_nilai' => $request->bobot_nilai,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'waktu_per_soal_detik' => $request->waktu_per_soal_detik,
            'aktif' => true,
            'kode_soal' => $request->kode_soal,
            'penjelasan' => $request->penjelasan,
        ]);
        return redirect()->route('admin.soalpsikotes.index')
            ->with('success', 'Soal psikotes berhasil dibuat.');
    }
    public function show($id)
    {
        $soal = SoalPsikotes::with('kategori')->findOrFail($id);
        return view('admin.soalpsikotes.show', compact('soal'));
    }
    public function edit($id)
    {
        $soal = SoalPsikotes::findOrFail($id);
        $kategoris = KategoriPsikotes::all();
        return view('admin.soalpsikotes.edit', compact('soal', 'kategoris'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori_psikotes,id',
            'pertanyaan' => 'required|string',
            'jenis' => 'required|in:kognitif,kepribadian,minat',
            'opsi_jawaban_text' => 'required|array|min:2',
            'kunci_jawaban' => 'nullable|string|max:10',
            'bobot_nilai' => 'required|integer|min:1',
            'tingkat_kesulitan' => 'required|integer|min:1|max:5',
            'waktu_per_soal_detik' => 'required|integer|min:10',
            'kode_soal' => 'required|string|unique:soal_psikotes,kode_soal,' . $id,
            'penjelasan' => 'nullable|string',
        ]);
        $soal = SoalPsikotes::findOrFail($id);
        $opsiJawaban = [];
        foreach ($request->opsi_jawaban_text as $index => $opsi) {
            if (!empty($opsi)) {
                $huruf = chr(65 + $index);
                $opsiJawaban[$huruf] = $opsi;
            }
        }
        $soal->update([
            'kategori_id' => $request->kategori_id,
            'pertanyaan' => $request->pertanyaan,
            'jenis' => $request->jenis,
            'opsi_jawaban' => $opsiJawaban,
            'kunci_jawaban' => $request->kunci_jawaban,
            'bobot_nilai' => $request->bobot_nilai,
            'tingkat_kesulitan' => $request->tingkat_kesulitan,
            'waktu_per_soal_detik' => $request->waktu_per_soal_detik,
            'kode_soal' => $request->kode_soal,
            'penjelasan' => $request->penjelasan,
        ]);
        return redirect()->route('admin.soalpsikotes.index')
            ->with('success', 'Soal psikotes berhasil diperbarui.');
    }
    public function destroy($id)
    {
        $soal = SoalPsikotes::findOrFail($id);
        $soal->delete();
        return redirect()->route('admin.soalpsikotes.index')
            ->with('success', 'Soal psikotes berhasil dihapus.');
    }
    public function downloadTemplate()
    {
        return Excel::download(new SoalTemplateExport, 'template-soal-psikotes.xlsx');
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,xls,xlsx|max:5120',
        ]);
        $file = $request->file('file');
        $imported = 0;
        $errors = [];
        try {
            if (in_array($file->getClientOriginalExtension(), ['csv', 'txt'])) {
                $csv = Reader::createFromPath($file->getPathname(), 'r');
                $csv->setHeaderOffset(0);
                $records = $csv->getRecords();
                foreach ($records as $index => $row) {
                    $this->prosesBarisSoal($row, $index + 2, $imported, $errors);
                }
            } else {
                $rows = Excel::toArray(new class implements \Maatwebsite\Excel\Concerns\ToCollection, \Maatwebsite\Excel\Concerns\WithHeadingRow {
                    public function collection(Collection $rows)
                    {
                        return $rows;
                    }
                }, $file);
                if (isset($rows[0]) && count($rows[0]) > 0) {
                    foreach ($rows[0] as $index => $row) {
                        $this->prosesBarisSoal((array)$row, $index + 2, $imported, $errors);
                    }
                } else {
                    $errors[] = 'File tidak memiliki data atau format tidak sesuai.';
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membaca file: ' . $e->getMessage());
        }
        if ($imported > 0) {
            $msg = "Berhasil mengimpor <strong>$imported soal</strong>.";
            if (!empty($errors)) {
                $msg .= ' Ditemukan ' . count($errors) . ' error.';
            }
            return redirect()->route('admin.soalpsikotes.index')->with('success', $msg);
        }
        return redirect()->route('admin.soalpsikotes.index')
            ->with('error', 'Tidak ada soal yang berhasil diimpor. <br>' . (!empty($errors) ? implode('<br>', $errors) : 'File tidak memiliki data yang valid.'));
    }
    private function prosesBarisSoal($row, $line, &$imported, &$errors)
    {
        $data = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(str_replace([' ', '-', '_'], '', $key));
            $data[$normalizedKey] = trim($value);
        }

        if (empty($data)) {
            $errors[] = "Baris $line: Data kosong, dilewati.";
            return;
        }

        $mapping = [
            'kodesoal' => ['kodesoal', 'kode', 'kode_soal', 'kodesoal'],
            'kategoriid' => ['kategoriid', 'kategori_id', 'kategori', 'idkategori'],
            'pertanyaan' => ['pertanyaan', 'soal', 'question', 'text'],
            'jenis' => ['jenis', 'type', 'tipe'],
            'opsi_a' => ['opsi_a', 'opsia', 'a', 'option_a'],
            'opsi_b' => ['opsi_b', 'opsib', 'b', 'option_b'],
            'opsi_c' => ['opsi_c', 'opsic', 'c', 'option_c'],
            'opsi_d' => ['opsi_d', 'opsid', 'd', 'option_d'],
            'opsi_e' => ['opsi_e', 'opsie', 'e', 'option_e'],
            'kuncijawaban' => ['kuncijawaban', 'kunci', 'jawaban', 'answer', 'key'],
            'bobotnilai' => ['bobotnilai', 'bobot', 'nilai', 'score', 'point'],
            'tingkatkesulitan' => ['tingkatkesulitan', 'kesulitan', 'difficulty', 'level'],
            'waktupersoaldetik' => ['waktupersoaldetik', 'waktu', 'time', 'duration'],
            'penjelasan' => ['penjelasan', 'explanation', 'desc']
        ];

        $extractedData = [];
        foreach ($mapping as $field => $possibleNames) {
            $extractedData[$field] = null;
            foreach ($possibleNames as $name) {
                if (isset($data[$name]) && !empty($data[$name])) {
                    $extractedData[$field] = $data[$name];
                    break;
                }
            }
        }

        if (empty($extractedData['kodesoal']) && empty($extractedData['pertanyaan'])) {
            $errors[] = "Baris $line: Tidak ada data yang valid (kode_soal atau pertanyaan kosong).";
            return;
        }

        if (empty($extractedData['kodesoal'])) {
            $extractedData['kodesoal'] = 'SOAL-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }

        $validator = Validator::make($extractedData, [
            'kodesoal' => 'required|string|unique:soal_psikotes,kode_soal',
            'kategoriid' => 'required|exists:kategori_psikotes,id',
            'pertanyaan' => 'required|string',
            'jenis' => 'required|in:kognitif,kepribadian,minat',
            'opsi_a' => 'required|string',
            'opsi_b' => 'required|string',
            'kuncijawaban' => 'nullable|in:A,B,C,D,E,a,b,c,d,e',
            'bobotnilai' => 'nullable|integer|min:1',
            'tingkatkesulitan' => 'nullable|integer|between:1,5',
            'waktupersoaldetik' => 'nullable|integer|min:10',
        ], [
            'kategoriid.exists' => 'Kategori ID tidak ditemukan dalam database.',
            'kuncijawaban.in' => 'Kunci jawaban harus A, B, C, D, atau E.',
            'kodesoal.unique' => 'Kode soal sudah ada di database.',
        ]);

        if ($validator->fails()) {
            $errors[] = "Baris $line: " . implode(", ", $validator->errors()->all());
            return;
        }

        $opsi = [];
        foreach (['a', 'b', 'c', 'd', 'e'] as $huruf) {
            $key = 'opsi_' . $huruf;
            if (!empty($extractedData[$key])) {
                $opsi[strtoupper($huruf)] = $extractedData[$key];
            }
        }

        if (count($opsi) < 2) {
            $errors[] = "Baris $line: Soal harus memiliki minimal 2 opsi jawaban.";
            return;
        }

        try {
            SoalPsikotes::create([
                'kode_soal' => $extractedData['kodesoal'],
                'kategori_id' => $extractedData['kategoriid'],
                'pertanyaan' => $extractedData['pertanyaan'],
                'jenis' => $extractedData['jenis'],
                'opsi_jawaban' => $opsi,
                'kunci_jawaban' => !empty($extractedData['kuncijawaban']) ? strtoupper($extractedData['kuncijawaban']) : null,
                'bobot_nilai' => !empty($extractedData['bobotnilai']) ? $extractedData['bobotnilai'] : 5,
                'tingkat_kesulitan' => !empty($extractedData['tingkatkesulitan']) ? $extractedData['tingkatkesulitan'] : 3,
                'waktu_per_soal_detik' => !empty($extractedData['waktupersoaldetik']) ? $extractedData['waktupersoaldetik'] : 60,
                'penjelasan' => $extractedData['penjelasan'] ?? null,
                'aktif' => true,
            ]);
            $imported++;
        } catch (\Exception $e) {
            $errors[] = "Baris $line: Gagal simpan - " . $e->getMessage();
        }
    }
    public function showForPelamar()
    {
        $user = Auth::user();
        $pelamar = $user->pelamar;
        if (!$pelamar) {
            return redirect()->route('pelamar.dashboard')->with('error', 'Data pelamar tidak ditemukan.');
        }
        $tahapanPsikotes = PelamarTahapan::where('pelamar_id', $pelamar->id)
            ->whereHas('tahapan', function ($query) {
                $query->where('nama_tahapan', 'like', '%psikotes%');
            })
            ->with(['tahapan', 'lowongan'])
            ->get();
        return view('pelamar.psikotes.index', compact('tahapanPsikotes'));
    }
}
class SoalTemplateExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        return collect([
            ['LOG001', '1', 'Jika semua pegawai harus hadir Senin-Jumat, dan hari ini adalah Sabtu, maka:', 'kognitif', 'Pegawai harus hadir', 'Pegawai tidak perlu hadir', 'Hanya manajer yang hadir', 'Tidak bisa disimpulkan', '', 'B', '5', '3', '60', 'Soal silogisme logika dasar'],
            ['NUM001', '2', 'Lanjutkan deret angka: 2, 4, 8, 16, ...', 'kognitif', '20', '24', '32', '36', '', 'C', '5', '3', '75', 'Tes kemampuan numerik dan pola angka'],
            ['VER001', '3', 'Sinonim dari kata "Inovatif" adalah:', 'kognitif', 'Kreatif', 'Konvensional', 'Pasif', 'Tradisional', '', 'A', '4', '2', '45', 'Tes kosakata dan pemahaman verbal'],
            ['BAC001', '4', '(Berdasarkan paragraf) Perusahaan X ingin meningkatkan produktivitas. Solusi yang disarankan adalah pelatihan staf dan otomasi. Apa solusi utama?', 'kognitif', 'Gaji lebih tinggi', 'Pelatihan staf', 'Libur lebih banyak', 'Penambahan staf', '', 'B', '5', '4', '90', 'Tes pemahaman bacaan'],
            ['MBTI001', '5', 'Saya merasa lebih nyaman dengan rencana yang sudah ditentukan daripada improvisasi.', 'kepribadian', 'Sangat Setuju', 'Setuju', 'Netral', 'Tidak Setuju', 'Sangat Tidak Setuju', 'A', '1', '2', '45', 'Indikator tipe J (Judging) dalam MBTI'],
            ['DISC001', '6', 'Dalam tim, saya cenderung mengambil inisiatif dan memimpin diskusi.', 'kepribadian', 'Sangat Setuju', 'Setuju', 'Netral', 'Tidak Setuju', 'Sangat Tidak Setuju', 'A', '1', '2', '45', 'Indikator tipe D (Dominance) dalam DISC'],
            ['MIN001', '7', 'Saya paling menikmati pekerjaan yang melibatkan:', 'minat', 'Menganalisis data', 'Merancang produk', 'Mengajar orang lain', 'Mengelola proyek', '', 'C', '1', '2', '45', 'Mengukur minat pada bidang edukasi'],
            ['IQ001', '8', 'Mana yang berbeda dari yang lain?', 'kognitif', 'Segitiga', 'Persegi', 'Lingkaran', 'Garis lurus', '', 'D', '5', '4', '60', 'Tes kecerdasan umum (abstraksi)'],
            ['KET001', '9', 'Dalam teks berikut, hitung berapa kali huruf "a" muncul: "analisis data sangat penting"', 'kognitif', '4', '5', '6', '7', '', 'C', '4', '3', '75', 'Tes ketelitian dan fokus'],
            ['SES001', '10', 'Saya merasa cocok bekerja di posisi yang membutuhkan:', 'minat', 'Analisis mendalam', 'Kreativitas tinggi', 'Pelayanan kepada orang lain', 'Pengambilan keputusan cepat', '', 'C', '1', '2', '45', 'Menilai kecocokan peran berdasarkan nilai kerja']
        ]);
    }
    public function headings(): array
    {
        return [
            'kode_soal',
            'kategori_id',
            'pertanyaan',
            'jenis',
            'opsi_A',
            'opsi_B',
            'opsi_C',
            'opsi_D',
            'opsi_E',
            'kunci_jawaban',
            'bobot_nilai',
            'tingkat_kesulitan',
            'waktu_per_soal_detik',
            'penjelasan'
        ];
    }
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4A90E2']]],
            'A:D' => ['alignment' => ['wrapText' => true]],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A1:N' . $event->sheet->getHighestRow();
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                foreach (range('A', 'N') as $col) {
                    $event->sheet->getColumnDimension($col)->setAutoSize(true);
                }
                $event->sheet->getStyle('C:C')->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('N:N')->getAlignment()->setWrapText(true);
                $event->sheet->getRowDimension(1)->setRowHeight(25);
            },
        ];
    }
}
