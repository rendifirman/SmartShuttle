<?php

namespace App\Helpers;

use App\Models\MasterData;

class MasterDataHelper
{
    /**
     * Ambil konten master data berdasarkan kode
     */
    public static function getContent(string $code): string
    {
        $data = MasterData::byCode($code)->first();
        return $data ? $data->content_html : '';
    }

    /**
     * Ambil semua master data berdasarkan tipe
     */
    public static function getByType(string $type): array
    {
        return MasterData::byType($type)
            ->orderBy('order')
            ->get()
            ->toArray();
    }

    /**
     * Ambil master data tertentu dengan fallback
     */
    public static function getWithFallback(string $code, string $fallback = ''): string
    {
        $content = self::getContent($code);
        return $content ?: $fallback;
    }
}