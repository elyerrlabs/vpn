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
        Schema::create('vpn_wireguards', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug', 150)->index();
            $table->string('subnet', 32);
            $table->integer('mtu');
            $table->string('gateway');
            $table->text('private_key');
            $table->integer('listen_port');
            $table->string('dns')->nullable();
            $table->boolean('dns_enabled')->default(false);
            $table->string('network_interface');
            $table->boolean('mounted')->default(false);
            $table->boolean('public')->default(false);
            $table->uuid('server_id');
            $table->timestamps();

            $table->unique(['server_id','listen_port']);
            $table->unique(['server_id','subnet']);

            $table->foreign('server_id')->references('id')->on('vpn_servers')->restrictOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vpn_wireguards');
    }
};
