<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PdfTextExtractor
{
    /**
     * Extract text from PDF using pdftotext
     */
    public function extract($pdfPath)
    {
        if (!file_exists($pdfPath)) {
            return '';
        }

        $cmd = 'pdftotext -layout ' . escapeshellarg($pdfPath) . ' -';
        $text = shell_exec($cmd);

        return trim($text ?? '');
    }

    /**
     * OCR fallback for scanned PDFs
     */
    public function extractViaOcr($pdfPath)
    {
        if (!file_exists($pdfPath)) {
            return '';
        }

        $tmpDir = sys_get_temp_dir() . '/ats_' . uniqid();
        mkdir($tmpDir);

        // PDF → images
        shell_exec(
            "pdftoppm -png " . escapeshellarg($pdfPath) . " $tmpDir/page"
        );

        $text = '';

        foreach (glob("$tmpDir/*.png") as $img) {
            $text .= shell_exec(
                "tesseract " . escapeshellarg($img) . " stdout"
            );
        }

        // Cleanup
        array_map('unlink', glob("$tmpDir/*"));
        rmdir($tmpDir);

        return trim($text);
    }
}
