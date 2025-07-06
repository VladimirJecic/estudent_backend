<?php

namespace External\Reports\model;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\View as ViewFacade; 
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
class ExcelTemplate implements FromView, WithEvents, WithColumnWidths{
    private string $pathToTemplate;
    /** @var array<string, mixed> */
    private array $model = [];
    private string $fileName;
    private int $columnCount = 0;

    private bool $hasFooter = false;

    public function __construct(string $pathToTemplate)
    {
        $this->pathToTemplate = $pathToTemplate;
    }

    public function withFooter(){
        $this->hasFooter = true;
        return $this;
    }

    public function view(): View
    {
        return ViewFacade::make($this->pathToTemplate, $this->model);
    }

    public function process($model, string $fileName, int $columnCount): BinaryFileResponse
    {
        $this->model = $model;
        $this->fileName = $fileName;
        $this->columnCount = $columnCount;
        return Excel::download($this, $this->fileName);
    }

    public function columnWidths(): array
    {
        $widths = [];

        for ($i = 1; $i <= $this->columnCount; $i++) {
            $letter = Coordinate::stringFromColumnIndex($i);
            $widths[$letter] = 30;
        }

        return $widths;
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                if ($this->columnCount <= 0) return;

                $lastColumn = Coordinate::stringFromColumnIndex($this->columnCount);
                $headerRow = 2;
                $headerRange = "A{$headerRow}:{$lastColumn}{$headerRow}";

                $event->sheet->getDelegate()->getStyle($headerRange)->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9EAD3'],
                    ],
                    'alignment' => ['horizontal' => 'center'],
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);

                $dataRowCount = 0;
                foreach ($this->model as $item) {
                    if (is_array($item)) {
                        $dataRowCount = count($item);
                        break;
                    }
                }

                // Account for optional footer
                if ($this->hasFooter) {
                    $dataRowCount = max(0, $dataRowCount - 1);
                }

                // Add 2: one for the main heading, one for the header row
                $lastRow = 2 + $dataRowCount;

                $dataRange = "A2:{$lastColumn}{$lastRow}";

                $event->sheet->getDelegate()->getStyle($dataRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN],
                    ],
                ]);
            },
        ];
    }
}