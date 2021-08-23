<?php

namespace SIMP2\SDK;

use Illuminate\Support\Facades\Storage;
use SIMP2\SDK\Reconcile\Detail;
use SIMP2\SDK\Reconcile\Footer;
use SIMP2\SDK\Reconcile\Header;
use SIMP2\SDK\Reconcile\Reconciler;

// Utility class to create the simp2 expected reconcile file.
class SIMP2Reconciler implements Reconciler
{
    private Header $header;
    private array $details;
    private Footer $footer;

    public function addHeader(Header $header): void
    {
        $this->header = $header;
    }

    public function addFooter(Footer $footer): void
    {
        $this->footer = $footer;
    }

    public function addDetail(Detail $detail): void
    {
        $this->details[] = $detail;
    }

    public function generateFile(string $disk, string $filename): void
    {
        Storage::disk($disk)->append($filename, $this->header->getCSV());
        foreach ($this->details as $detail) {
            Storage::disk($disk)->append($filename, $detail->getCSV());
        }
        Storage::disk($disk)->append($filename, $this->footer->getCSV());
    }
}
