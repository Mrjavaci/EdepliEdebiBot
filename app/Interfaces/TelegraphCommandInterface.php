<?php

namespace App\Interfaces;

interface TelegraphCommandInterface
{
    public function handleCommand(mixed $arguments = null): void;
}
