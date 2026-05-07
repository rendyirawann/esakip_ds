<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class MigrateYiiUsers extends Command
{
    protected $signature = 'migrate:yii-users';
    protected $description = 'Migrasi data user dari Yii2 MySQL ke Laravel (Fix Sequence Order)';

    public function handle()
    {
        $this->info("Membersihkan tabel users...");
        DB::connection('pgsql')->statement('TRUNCATE TABLE users RESTART IDENTITY CASCADE');

        $this->info("Mengambil data user dari MySQL...");
        $users = DB::connection('yii_mysql')->table('user')->get();

        foreach ($users as $user) {
            $this->line("Memproses user: {$user->username}");

            DB::connection('pgsql')->table('users')->insert([
                'id'                => $user->id,
                'name'              => $user->username,
                'username'          => $user->username,
                'email'             => $user->email,
                'password'          => $user->password_hash,
                'email_verified_at' => Carbon::createFromTimestamp($user->created_at),
                'created_at'        => Carbon::createFromTimestamp($user->created_at),
                'updated_at'        => Carbon::createFromTimestamp($user->updated_at),
                'is_active'         => $user->status === 10,
            ]);
        }

        $this->info("Sinkronisasi Sequence PostgreSQL...");
        // PENTING: Reset sequence SETELAH data manual masuk agar auto-increment tau nomor selanjutnya
        $maxId = DB::connection('pgsql')->table('users')->max('id') ?? 0;
        $nextId = $maxId + 1;
        DB::connection('pgsql')->statement("SELECT setval(pg_get_serial_sequence('users', 'id'), {$nextId}, false)");

        $this->info("Menambahkan akun Superadmin di ID terakhir ({$nextId})...");
        
        $superAdmin = User::create([
            'name'              => 'Super Administrator',
            'username'          => 'superadmin',
            'email'             => 'superadmin@gmail.com',
            'password'          => Hash::make('12qwaszx123!!@@##'),
            'email_verified_at' => now(),
            'is_active'         => true,
        ]);

        try {
            $superAdmin->assignRole('Superadmin');
            $this->info("Role Superadmin berhasil diberikan.");
        } catch (\Exception $e) {
            $this->warn("Gagal assign role: " . $e->getMessage());
        }

        $this->info("\nMigrasi Selesai!");
        $this->info("Superadmin baru Anda berada di ID: {$superAdmin->id}");
    }
}