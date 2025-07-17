<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RehashUserPasswords extends Command
{
    protected $signature = 'users:rehash-passwords {--force : Force rehash even if passwords are already hashed}';
    protected $description = 'Rehash all user passwords (useful for security updates)';

    public function handle()
    {
        $force = $this->option('force');

        if ($force) {
            $this->warn('Modo forçado ativado - todas as senhas serão re-criptografadas!');
        } else {
            $this->info('Verificando usuários com senhas que precisam ser re-criptografadas...');
        }

        $users = User::all();

        if ($users->isEmpty()) {
            $this->info('Nenhum usuário encontrado.');
            return;
        }

        $updated = 0;
        foreach ($users as $user) {
            // Se não for forçado, só atualiza senhas que não estão hasheadas
            if (!$force && strlen($user->password) >= 60) {
                continue;
            }

            $user->password = Hash::make($user->password);
            $user->save();
            $this->info("Senha re-criptografada para o usuário: {$user->email}");
            $updated++;
        }

        if ($updated === 0) {
            $this->info('Nenhuma senha foi atualizada.');
        } else {
            $this->info("Processo concluído! {$updated} senha(s) atualizada(s) com sucesso.");
        }
    }
}
