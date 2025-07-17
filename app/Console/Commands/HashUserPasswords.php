<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class HashUserPasswords extends Command
{
    protected $signature = 'users:hash-passwords';
    protected $description = 'Hash all user passwords that are not already hashed';

    public function handle()
    {
        $this->info('Verificando usuários com senhas não criptografadas...');

        $users = User::whereRaw('CHAR_LENGTH(password) < 60')->get(); // Filtra usuários com senhas curtas (não bcrypt)

        if ($users->isEmpty()) {
            $this->info('Todas as senhas já estão criptografadas.');
            return;
        }

        foreach ($users as $user) {
            $user->password = Hash::make($user->password);
            $user->save();
            $this->info("Senha criptografada para o usuário: {$user->email}");
        }

        $this->info('Processo concluído! Senhas atualizadas com sucesso.');
    }
}
