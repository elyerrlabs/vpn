<?php

namespace Vpn\App\Console\Commands;

use Illuminate\Console\Command;

/*
 * VPN - Server-side software for centralized administration and node management of a VPN service.
 * Copyright (C) 2025 Elvis Yerel Roman Concha
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

class KeysGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "keys:generator {--force : Overwrite existing keys without confirmation}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Generate keys";

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $keyGen = new \Vpn\App\Services\KeysGenerator();
        $force = $this->option('force');

        if (file_exists("$keyGen->path/priv.pem") && !$force) {
            if (!$this->confirm(__('The keys already exist. Do you want to overwrite them?'))) {
                $this->info(__('Key generation canceled.'));
                return;
            }
        }

        $keyGen->generateKeys(true);
        $this->info(__('Keys successfully generated in :path', ['path' => $keyGen->path]));
    }
}
