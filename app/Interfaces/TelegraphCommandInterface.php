<?php

namespace App\Interfaces;

interface TelegraphCommandInterface
{
    public static function getDescription();

    public function handleCommand(mixed $arguments = null): void;
}
