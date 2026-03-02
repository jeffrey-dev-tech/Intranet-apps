<?php

namespace App\Http\Controllers;

use Mpdf\Mpdf;
use Illuminate\Http\Request;
use App\Services\GoogleService;
use Carbon\Carbon;

class GoogleDriveController extends Controller
{
    protected GoogleService $google;

    // Root Google Drive folder ID
    private string $driveFolderId = '1kYyrP1mLxAglgTVVa4H0rJ4O62AVkJqI';

    public function __construct(GoogleService $google)
    {
        $this->google = $google;
    }

    /**
     * Show upload form and list files
     */
    public function listFiles(Request $request)
    {
        $parentFolderId = $this->driveFolderId;

        // Get all items in parent folder
        $allItems = $this->google->listFilesInFolder($parentFolderId);

        // Filter only folders (Month-Year folders)
        $monthFolders = array_filter($allItems, fn($file) => $file['mimeType'] === 'application/vnd.google-apps.folder');

        // Get folder selected by user
        $selectedFolderId = $request->query('folder_id', null);

        $files = [];
        if ($selectedFolderId) {
            $files = $this->google->listFilesInFolder($selectedFolderId);
        }

        return view('coldchain.list', compact('files', 'monthFolders', 'selectedFolderId'));
    }

    /**
     * Upload PDF from form content
     */
public function uploadPdfFromForm(Request $request)
{
    $request->validate([
        'content' => 'required|string',
        'pdf_name' => 'required|string',
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validate images
    ]);

    $content = $request->input('content');
    $pdfNameInput = $request->input('pdf_name');

    $images = [];
    if($request->hasFile('images')){
        foreach($request->file('images') as $file){
            $filename = time().'_'.$file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $images[] = $filename;
        }
    }

    $date = Carbon::now();
    $filename = $pdfNameInput . '_' . $date->format('Ymd_His') . '.pdf';

    $html = view('sanden_forms.ColdChainReport', compact('content', 'images'))->render();

    try {
        $mpdf = new \Mpdf\Mpdf(['tempDir' => storage_path('tmp/mpdf')]);
        $mpdf->WriteHTML($html);
        $pdfContent = $mpdf->Output('', \Mpdf\Output\Destination::STRING_RETURN);

        $fileId = $this->google->uploadPdfToMonthFolder($pdfContent, $filename, $this->driveFolderId);

        return response()->json([
            'success' => 'PDF uploaded successfully',
            'upload_time' => $date->format('d M Y h:i A'),
            'file_id' => $fileId,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Failed to upload PDF: ' . $e->getMessage(),
        ], 500);
    }
}
    /**
     * Download a file from Google Drive
     */
    public function download(string $fileId, string $fileName)
    {
        try {
            return $this->google->downloadFile($fileId, $fileName);
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Failed to download file: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the upload form
     */
    public function form()
    {
        return view('coldchain.upload');
    }
}
