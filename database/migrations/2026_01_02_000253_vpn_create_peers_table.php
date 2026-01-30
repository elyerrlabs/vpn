<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vpn_peers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 150);
            $table->string('public_key');
            $table->string('preshared_key');
            $table->string('allowed_ips');
            $table->string('persistent_keepalive'); 
            $table->boolean('mounted')->default(false);
            $table->boolean('stand_by')->default(false);
            $table->uuid('user_id')->index();
            $table->uuid('wireguard_id')->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreign('wireguard_id')->references('id')->on('vpn_wireguards')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vpn_peers');
    }
};
