<?php

namespace App\Http\Controllers;

use FFMpeg;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class VideoController extends Controller
{
    public function renderAllCyberSecurityVideos()
    {
        $folder = 'cyber_security_videos'; // source videos
        $files = Storage::disk('public')->files($folder);

        if (empty($files)) {
            Log::info('No videos found in the folder: ' . $folder);
            return redirect()->back()->with('success', 'No videos found to process.');
        }

        // Clear old processed videos
        $processedFiles = Storage::disk('public')->files('processed');
        if (!empty($processedFiles)) {
            Storage::disk('public')->delete($processedFiles);
            Log::info('Cleared old processed videos.');
        }

        // Create processed folder if it doesn't exist
        if (!Storage::disk('public')->exists('processed')) {
            Storage::disk('public')->makeDirectory('processed');
            Log::info('Created processed folder.');
        }

        // Initialize FFMpeg
        $ffmpeg = \FFMpeg\FFMpeg::create([
            'ffmpeg.binaries'  => '/usr/bin/ffmpeg',
            'ffprobe.binaries' => '/usr/bin/ffprobe',
            'timeout' => 3600,
            'ffmpeg.threads' => 12
        ]);

        foreach ($files as $file) {
            try {
                $inputPath = storage_path('app/public/' . $file);
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $safeFilename = preg_replace('/[^A-Za-z0-9_\-]/', '_', $filename);
                $outputPath = storage_path("app/public/processed/{$safeFilename}_processed.mp4");

                Log::info("Processing video: {$file}");

                $video = $ffmpeg->open($inputPath);

                // Use explicit format with audio
                $format = new \FFMpeg\Format\Video\X264('libmp3lame', 'libx264');
                $format->setKiloBitrate(1500); // optional: adjust quality
                $format->setAudioCodec('aac');

                $video->save($format, $outputPath);

                Log::info("Successfully processed: {$file} -> {$safeFilename}_processed.mp4");

            } catch (\Exception $e) {
                Log::error("Error processing video {$file}: " . $e->getMessage());
            }
        }

        Log::info('All videos processed in folder: ' . $folder);

        return redirect()->back()->with('success', 'All Cyber Security videos processed! Check the "processed" folder.');
    }
}
