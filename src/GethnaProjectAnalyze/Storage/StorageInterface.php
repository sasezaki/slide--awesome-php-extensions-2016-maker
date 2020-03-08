<?php
declare(strict_types=1);

namespace GethnaProjectAnalyze\Storage;

interface StorageInterface
{
    public function has(string $key) : bool;
    public function get(string $key) : string;
    public function save(string $key, string $value) : bool;
}