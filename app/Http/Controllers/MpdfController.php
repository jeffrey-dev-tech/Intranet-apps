<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MpdfController extends Controller
{
    public function generate()
    {
        if (!class_exists(\Mpdf\Mpdf::class)) {
            return response()->json([
                'error' => 'mPDF is not installed. Run: composer require mpdf/mpdf'
            ], 500);
        }

        // Create temp folder if missing
        $tempDir = storage_path('app/mpdf');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0775, true);
        }

        // Create mPDF instance with custom temp directory
        $mpdf = new \Mpdf\Mpdf([
            'tempDir' => $tempDir
        ]);

        // Render Blade view to HTML
        $html = view('letters.template')->render();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // View in browser
        return response($mpdf->Output('letter.pdf', 'I'))
            ->header('Content-Type', 'application/pdf');
    }
public function collection()
{
    if (!class_exists(\Mpdf\Mpdf::class)) {
        return response()->json([
            'error' => 'mPDF is not installed. Run: composer require mpdf/mpdf'
        ], 500);
    }

    // Create temp folder if missing
    $tempDir = storage_path('app/mpdf');
    if (!is_dir($tempDir)) {
        mkdir($tempDir, 0775, true);
    }

    // Create mPDF instance with custom temp directory
    $mpdf = new \Mpdf\Mpdf([
        'tempDir' => $tempDir,
        'format'  => 'A3', // 8.5 x 11 in
          'margin_top' => 2,
    'margin_bottom' => 2,
    'margin_left' => 2,
    'margin_right' => 2,
    ]);

    // Render Blade view to HTML
    $html = view('letters.collection')->render();

    // Write HTML to PDF
    $mpdf->WriteHTML($html);

    // Add cut line in the middle of the page (horizontally at 5.5 inches)
    $pageWidth = $mpdf->w;   // full page width in points
    $pageHeight = $mpdf->h;  // full page height in points

    // Draw dashed line across the width at half the height
    $mpdf->SetLineWidth(0.5);
    $mpdf->SetDash(3, 2); // dashed line: 3 on, 2 off
    $mpdf->Line(0, $pageHeight / 2, $pageWidth, $pageHeight / 2);

    // Reset dash style back to solid
    $mpdf->SetDash();

    // View in browser
    return response($mpdf->Output('letter.pdf', 'I'))
        ->header('Content-Type', 'application/pdf');
}

}
