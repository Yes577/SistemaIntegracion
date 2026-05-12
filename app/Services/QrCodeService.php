<?php

namespace App\Services;

use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrCodeService
{
    public function generateSvg(string $content): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle((int) config('eventos.qr.svg_size', 360), 4),
            new SvgImageBackEnd()
        );

        return (new Writer($renderer))->writeString($content, ecLevel: ErrorCorrectionLevel::M());
    }

    public function generateDataUri(string $content): string
    {
        return 'data:image/svg+xml;base64,'.base64_encode($this->generateSvg($content));
    }

    public function generatePng(string $content): string
    {
        $matrix = Encoder::encode($content, ErrorCorrectionLevel::M())->getMatrix();
        $matrixSize = $matrix->getWidth();
        $margin = 4;
        $imageSize = (int) config('eventos.qr.svg_size', 360);
        $totalModules = $matrixSize + ($margin * 2);
        $moduleSize = max(1, (int) floor($imageSize / $totalModules));
        $pngSize = $totalModules * $moduleSize;

        $raw = '';

        for ($y = 0; $y < $pngSize; $y++) {
            $raw .= "\x00";

            $moduleY = intdiv($y, $moduleSize) - $margin;

            for ($x = 0; $x < $pngSize; $x++) {
                $moduleX = intdiv($x, $moduleSize) - $margin;
                $isDark = $moduleX >= 0
                    && $moduleX < $matrixSize
                    && $moduleY >= 0
                    && $moduleY < $matrixSize
                    && $matrix->get($moduleX, $moduleY) === 1;

                $raw .= $isDark ? "\x00\x00\x00" : "\xFF\xFF\xFF";
            }
        }

        return $this->buildPng($pngSize, $pngSize, $raw);
    }

    private function buildPng(int $width, int $height, string $rawData): string
    {
        $signature = "\x89PNG\r\n\x1a\n";
        $ihdr = pack('NNC5', $width, $height, 8, 2, 0, 0, 0);
        $idat = gzcompress($rawData, 9);

        return $signature
            .$this->pngChunk('IHDR', $ihdr)
            .$this->pngChunk('IDAT', $idat)
            .$this->pngChunk('IEND', '');
    }

    private function pngChunk(string $type, string $data): string
    {
        $chunk = $type.$data;

        return pack('N', strlen($data))
            .$chunk
            .pack('N', crc32($chunk));
    }
}
