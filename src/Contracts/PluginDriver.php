<?php

namespace Meraki\Packages\Auth\Contracts;

interface PluginDriver
{
    public function activate(): void;

    public function deactivate(): void;

    public function isActive(): bool;

    public function driverName(): string;
}
