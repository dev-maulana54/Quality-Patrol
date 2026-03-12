<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\Model_data_patrol;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DownloadController extends Controller
{
    protected $dataPatrol;
    public function __construct()
    {
        // Cek apakah session isLoggedIn tidak ada atau tidak bernilai true, maka redirect ke login
        if (!session()->get('isLoggedIn') || session()->get('isLoggedIn') !== true) {
            header('Location: ' . base_url('login'));
            exit(); // ✅ WAJIB pakai exit() agar script berhenti
        }

        // Cek juga apakah npk tidak ada di session
        if (!session()->get('npk')) {
            header('Location: ' . base_url('login'));
            exit(); // ✅ WAJIB pakai exit()
        }
        $this->dataPatrol = new Model_data_patrol();
    }
    public function file($namaFile)
    {
        $path = FCPATH . 'assets/uploads/' . $namaFile;

        if (!file_exists($path)) {
            return $this->response->setStatusCode(404, 'File tidak ditemukan');
        }

        return $this->response->download($path, null)->setFileName($namaFile);
    }
    public function test_excel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal Patrol');
        $sheet->setCellValue('C1', 'Auditor');

        $sheet->setCellValue('A2', '1');
        $sheet->setCellValue('B2', '13 Mar 2026');
        $sheet->setCellValue('C2', 'Tester');

        $filename = 'test_excel.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
    public function export_excel()
    {
        $ids = $this->request->getPost('ids');

        if (empty($ids)) {
            exit('Tidak ada data');
        }

        $data = $this->dataPatrol->getPatrolByIds($ids);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Temuan Patrol');

        $headers = [
            'No',
            'Tanggal Patrol',
            'Auditor',
            'Auditee',
            'Area / Proses',
            'Temuan',
            'Analisa Penyebab',
            'Action',
            'PIC Action',
            'Due Date',
            'Status',
            'Evidence'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }

        /*
    ======================
    STYLE HEADER
    ======================
    */
        $headerStyle = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'BFBFBF' // RGB 191,191,191
                ]
            ]
        ];

        $sheet->getStyle('A1:L1')->applyFromArray($headerStyle);

        $rowNum = 2;
        $no = 1;

        foreach ($data as $row) {
            $sheet->setCellValue('A' . $rowNum, $no++);
            $sheet->setCellValue('B' . $rowNum, $row['tanggal_patrol']);
            $sheet->setCellValue('C' . $rowNum, $row['auditor_name']);
            $sheet->setCellValue('D' . $rowNum, $row['nama_auditee']);
            $sheet->setCellValue('E' . $rowNum, $row['section_name']);
            $sheet->setCellValue('F' . $rowNum, $row['deskripsi_temuan']);
            $sheet->setCellValue('G' . $rowNum, $row['analisa_penyebab']);
            $sheet->setCellValue('H' . $rowNum, $row['action']);
            $sheet->setCellValue('I' . $rowNum, $row['pic_departement_name']);
            $sheet->setCellValue('J' . $rowNum, $row['due_date']);
            $sheet->setCellValue('K' . $rowNum, $this->mapStatus($row['status']));

            // insert image evidence
            if (!empty($row['evidence_file'])) {
                $filePath = FCPATH . 'uploads/findings_evidence/' . $row['evidence_file'];

                if (file_exists($filePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Evidence');
                    $drawing->setDescription('Evidence');
                    $drawing->setPath($filePath);
                    $drawing->setHeight(80);
                    $drawing->setCoordinates('L' . $rowNum);
                    $drawing->setWorksheet($sheet);

                    $sheet->getRowDimension($rowNum)->setRowHeight(65);
                    $sheet->getColumnDimension('L')->setWidth(20);
                }
            }

            $rowNum++;
        }

        $lastRow = $rowNum - 1;

        /*
    ======================
    BORDER TABLE
    ======================
    */
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        $sheet->getStyle('A1:L' . $lastRow)->applyFromArray($borderStyle);

        /*
    ======================
    GLOBAL ALIGNMENT
    ======================
    */
        $sheet->getStyle('A1:L' . $lastRow)->getAlignment()->setVertical(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
        );

        $sheet->getStyle('A1:L' . $lastRow)->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
        );

        /*
    ======================
    WRAP TEXT
    ======================
    */
        $sheet->getStyle('F2:H' . $lastRow)->getAlignment()->setWrapText(true);

        /*
    ======================
    SHRINK TO FIT
    ======================
    */
        $sheet->getStyle('C2:C' . $lastRow)->getAlignment()->setShrinkToFit(true);

        /*
    ======================
    OPTIONAL: KHUSUS KOLOM TEKS PANJANG
    BIAR LEBIH ENAK DIBACA BISA LEFT ALIGN
    ======================
    */
        $sheet->getStyle('F2:H' . $lastRow)->getAlignment()->setHorizontal(
            \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT
        );

        /*
    ======================
    SET HEIGHT HEADER
    ======================
    */
        $sheet->getRowDimension(1)->setRowHeight(25);

        /*
    ======================
    AUTO SIZE
    ======================
    */
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'temuan_patrol_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function mapStatus($status)
    {
        switch ($status) {
            case 1:
                return 'Close';
            case 2:
                return 'In Progress';
            case 4:
                return 'Cancel';
            default:
                return 'Open';
        }
    }
}
