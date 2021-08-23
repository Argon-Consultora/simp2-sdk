<?php

namespace SIMP2\SDK\Reconcile;

interface Reconciler
{
    public function addHeader(Header $header): void;

    public function addFooter(Footer $footer): void;

    public function addDetail(Detail $detail): void;

    public function generateFile(string $disk, string $filename): void;
}
