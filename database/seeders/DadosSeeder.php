<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DadosSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedGuests();
        $this->seedDelegacoesPresenca();
        $this->seedGifts();
    }

    private function seedDelegacoesPresenca(): void
    {
        $file = database_path('dados/delegacoes_presenca.txt');
        if (!file_exists($file)) {
            $this->command->warn('Arquivo delegacoes_presenca.txt não encontrado.');
            return;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $guests = DB::table('guests')
            ->select('id', 'nome_completo')
            ->get();

        if ($guests->isEmpty()) {
            $this->command->warn('Nenhum convidado encontrado para criar delegações de presença.');
            return;
        }

        $guestIndex = [];
        foreach ($guests as $guest) {
            $key = $this->normalizeGuestName($guest->nome_completo);
            $guestIndex[$key][] = $guest->id;
        }

        $count = 0;
        $skipped = 0;

        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#') || str_starts_with($line, '--')) {
                continue;
            }

            [$responsavelRef, $dependenteRef] = $this->parseDelegacaoLine($line);

            if (empty($responsavelRef) || empty($dependenteRef)) {
                $this->command->warn('Linha de delegação inválida (esperado: "Responsável, Dependente"): ' . ($lineNumber + 1));
                $skipped++;
                continue;
            }

            $responsavelIds = $this->findGuestIdsByReference($responsavelRef, $guests, $guestIndex);
            $dependenteIds = $this->findGuestIdsByReference($dependenteRef, $guests, $guestIndex);

            if (count($responsavelIds) !== 1 || count($dependenteIds) !== 1) {
                $this->command->warn('Não foi possível mapear delegação na linha ' . ($lineNumber + 1) . ': "' . $responsavelRef . ' -> ' . $dependenteRef . '"');
                $skipped++;
                continue;
            }

            $responsavelId = $responsavelIds[0];
            $dependenteId = $dependenteIds[0];

            if ($responsavelId === $dependenteId) {
                $this->command->warn('Delegação ignorada (mesmo convidado como responsável e dependente) na linha ' . ($lineNumber + 1));
                $skipped++;
                continue;
            }

            DB::table('delegacoes_presenca')->updateOrInsert(
                [
                    'convidado_responsavel_id' => $responsavelId,
                    'convidado_dependente_id' => $dependenteId,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

            $count++;
        }

        $this->command->info("Delegações de presença inseridas/atualizadas: {$count}");
        if ($skipped > 0) {
            $this->command->warn("Delegações ignoradas: {$skipped}");
        }
    }

    private function seedGuests(): void
    {
        $file = database_path('dados/guests.txt');
        if (!file_exists($file)) {
            $this->command->warn('Arquivo convidados.txt não encontrado.');
            return;
        }

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $tipoMap = [
            'vó'    => 'ava',
            'vo'    => 'ava',
            'vô'    => 'avo',
            'avó'   => 'ava',
            'avô'   => 'avo',
            'noivo' => 'noivo',
            'noiva' => 'noiva',
        ];

        // Tipos que determinam sexo diretamente
        $tipoSexoMap = [
            'noiva'    => 'F',
            'madrinha' => 'F',
            'mae'      => 'F',
            'ava'      => 'F',
            'noivo'    => 'M',
            'padrinho' => 'M',
            'pai'      => 'M',
            'avo'      => 'M',
        ];

        // Primeiros nomes femininos brasileiros para inferência
        $nomesFemininos = [
            'ana', 'maria', 'leticia', 'letícia', 'fernanda', 'julia', 'júlia',
            'beatriz', 'bianca', 'bruna', 'camila', 'carolina', 'claudia',
            'cleusa', 'daniela', 'debora', 'débora', 'elaine', 'erica', 'érica',
            'fabiana', 'flavia', 'flávia', 'gabriela', 'giovanna', 'heloisa',
            'isabela', 'isabella', 'juliana', 'kelli', 'larissa', 'laura',
            'lara', 'livia', 'lívia', 'luisa', 'luísa', 'manuela', 'manoela',
            'marilene', 'mariana', 'marina', 'nathalia', 'natalia', 'natália',
            'patricia', 'patrícia', 'paula', 'priscila', 'rafaela', 'raquel',
            'renata', 'rita', 'roberta', 'sabrina', 'sandra', 'sara', 'silvia',
            'sílvia', 'simone', 'solange', 'soraya', 'stephanie', 'talita',
            'taissa', 'tatiana', 'thais', 'thaís', 'vanessa', 'viviane',
            'aline', 'alicia', 'alícia', 'agatha', 'ágatha', 'barbara', 'bárbara',
            'clara', 'cilene', 'dora', 'filomena', 'georgia', 'geórgia',
            'jessica', 'jéssica', 'julia', 'júlia', 'keli', 'luana', 'miriam',
            'miria', 'miriã', 'taissa', 'iara', 'nadia', 'nádia',
        ];

        $count = 0;

        // --- Noivos (contas especiais com senha customizada) ---
        $noivos = [
            [
                'nome_completo'       => 'Brendon Pietro',
                'telefone'            => null,
                'email'               => null,
                'senha'               => Hash::make(env('SENHA_NOIVOS', 'troque-esta-senha')),
                'tipo'                => 'noivo',
                'sexo'                => 'M',
                'presenca_confirmada' => 'S',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
            [
                'nome_completo'       => 'Leticia Mendes',
                'telefone'            => null,
                'email'               => null,
                'senha'               => Hash::make(env('SENHA_NOIVOS', 'troque-esta-senha')),
                'tipo'                => 'noiva',
                'sexo'                => 'F',
                'presenca_confirmada' => 'S',
                'created_at'          => now(),
                'updated_at'          => now(),
            ],
        ];

        foreach ($noivos as $noivo) {
            $existingNoivo = $this->findGuestByName($noivo['nome_completo']);
            if ($existingNoivo) {
                DB::table('guests')
                    ->where('id', $existingNoivo->id)
                    ->update([
                        'telefone' => $noivo['telefone'],
                        'email' => $noivo['email'],
                        'senha' => $noivo['senha'],
                        'tipo' => $noivo['tipo'],
                        'sexo' => $noivo['sexo'],
                        'presenca_confirmada' => 'S',
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('guests')->insert($noivo);
                $count++;
            }
        }

        $seenNames = [
            $this->normalizeGuestName('Brendon Pietro') => true,
            $this->normalizeGuestName('Leticia Mendes') => true,
        ];

        // --- Convidados do arquivo ---
        foreach ($lines as $lineNumber => $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#') || str_starts_with($line, '--')) {
                continue;
            }

            $parts = array_map('trim', explode(',', $line));

            $nome = $parts[0] ?? '';
            if (empty($nome)) continue;

            $normalizedName = $this->normalizeGuestName($nome);
            if (isset($seenNames[$normalizedName])) {
                $this->command->warn('Convidado duplicado ignorado na linha ' . ($lineNumber + 1) . ': ' . $nome);
                continue;
            }
            $seenNames[$normalizedName] = true;

            $telefone = !empty($parts[1]) ? preg_replace('/\D/', '', $parts[1]) : null;
            $email    = !empty($parts[2]) ? trim($parts[2]) : null;
            $tipo     = !empty($parts[3]) ? mb_strtolower(trim($parts[3])) : 'convidado';

            $tipo = $tipoMap[$tipo] ?? $tipo;

            // Inferir sexo: por tipo primeiro, depois por nome
            $sexo = $tipoSexoMap[$tipo] ?? null;
            if ($sexo === null) {
                $primeiroNome = mb_strtolower(explode(' ', trim($nome))[0]);
                $sexo = in_array($primeiroNome, $nomesFemininos) ? 'F' : 'M';
            }

            // Senha = últimos 4 dígitos do telefone (ou primeiro nome se sem telefone)
            if ($telefone && strlen($telefone) >= 4) {
                $senhaRaw = substr($telefone, -4);
            } else {
                $senhaRaw = mb_strtolower(explode(' ', trim($nome))[0]);
            }
            $senha = Hash::make($senhaRaw);

            $existingGuest = $this->findGuestByName($nome);
            if ($existingGuest) {
                DB::table('guests')
                    ->where('id', $existingGuest->id)
                    ->update([
                        'nome_completo' => $nome,
                        'telefone' => $telefone,
                        'email' => $email,
                        'senha' => $senha,
                        'tipo' => $tipo,
                        'sexo' => $sexo,
                        // Mantém confirmação já feita no sistema.
                        'presenca_confirmada' => $existingGuest->presenca_confirmada ?? 'N',
                        'updated_at' => now(),
                    ]);
            } else {
                DB::table('guests')->insert([
                    'nome_completo'       => $nome,
                    'telefone'            => $telefone,
                    'email'               => $email,
                    'senha'               => $senha,
                    'tipo'                => $tipo,
                    'sexo'                => $sexo,
                    'presenca_confirmada' => 'N',
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]);

                $count++;
            }
        }

        $this->command->info("Convidados inseridos: {$count}");
    }

    private function seedGifts(): void
    {
        $file = database_path('dados/gifts.txt');
        if (!file_exists($file)) {
            $this->command->warn('Arquivo gifts.txt não encontrado.');
            return;
        }

        // Limpa tabelas dependentes antes de recriar os presentes
        DB::table('gift_orders')->delete();
        DB::table('gifts')->delete();

        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $count = 0;

        $categoriasValidas = ['eletrodoméstico', 'eletrodomestico', 'louça', 'louca', 'limpeza', 'geral'];

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#') || str_starts_with($line, '--')) {
                continue;
            }

            $nome      = '';
            $descricao = null;
            $valor     = 0;
            $cotavel   = 'N';
            $numCotas  = null;
            $categoria = null;
            $favorito  = 'N';

            // Procura "R$" na linha para separar nome/desc do valor
            $rsPos = mb_strpos($line, 'R$');

            if ($rsPos !== false) {
                // Tudo antes do R$ = nome + descrição
                $beforeRs = rtrim(mb_substr($line, 0, $rsPos), ', ');
                // Tudo depois do R$ = valor + flags
                $afterRs = trim(mb_substr($line, $rsPos + 2));

                // Separar nome e descrição (primeira vírgula)
                $firstComma = mb_strpos($beforeRs, ',');
                if ($firstComma !== false) {
                    $nome      = trim(mb_substr($beforeRs, 0, $firstComma));
                    $descricao = trim(mb_substr($beforeRs, $firstComma + 1));
                } else {
                    $nome = trim($beforeRs);
                }

                // Parsear valor no formato brasileiro: 4.655,00 ou 192,00
                if (preg_match('/^\s*([\d.]+),(\d{2})\s*(.*)$/', $afterRs, $matches)) {
                    $valorStr = str_replace('.', '', $matches[1]) . '.' . $matches[2];
                    $valor    = (float) $valorStr;
                    $rest     = trim($matches[3], ', ');
                } elseif (preg_match('/^\s*([\d.]+)\s*(.*)$/', $afterRs, $matches)) {
                    $valor = (float) str_replace('.', '', $matches[1]);
                    $rest  = trim($matches[2], ', ');
                } else {
                    $rest = '';
                }

                // Parsear cotável, número de cotas, categoria e favorito
                if (!empty($rest)) {
                    $flags = array_map('trim', explode(',', $rest));
                    foreach ($flags as $flag) {
                        if (empty($flag)) continue;
                        $flagLower = mb_strtolower($flag);
                        if (in_array($flagLower, ['cotável', 'cotavel', 'sim'])) {
                            $cotavel = 'S';
                        } elseif (is_numeric($flag)) {
                            $numCotas = (int) $flag;
                        } elseif (in_array($flagLower, ['fav', 'favorito'])) {
                            $favorito = 'S';
                        } elseif (in_array($flagLower, $categoriasValidas)) {
                            // Normalizar acento
                            $categoria = match ($flagLower) {
                                'eletrodoméstico', 'eletrodomestico' => 'Eletrodoméstico',
                                'louça', 'louca' => 'Louça',
                                'limpeza' => 'Limpeza',
                                'geral' => 'Geral',
                                default => $flag,
                            };
                        }
                    }
                }
            } else {
                // Sem R$ = item sem preço ainda
                $parts     = array_map('trim', explode(',', $line));
                $nome      = $parts[0] ?? '';
                $descricao = !empty($parts[1]) ? $parts[1] : null;

                // Verificar se o último campo é categoria
                $lastPart = mb_strtolower(trim(end($parts)));
                if (in_array($lastPart, $categoriasValidas)) {
                    $categoria = match ($lastPart) {
                        'eletrodoméstico', 'eletrodomestico' => 'Eletrodoméstico',
                        'louça', 'louca' => 'Louça',
                        'limpeza' => 'Limpeza',
                        'geral' => 'Geral',
                        default => trim(end($parts)),
                    };
                    // Remover categoria da descrição se ficou lá
                    if ($descricao && mb_strtolower(trim($descricao)) === $lastPart) {
                        $descricao = null;
                    }
                }
            }

            if (empty($nome)) continue;
            if (empty($descricao)) $descricao = null;

            DB::table('gifts')->insert([
                'nome'           => $nome,
                'descricao'      => $descricao,
                'valor'          => $valor,
                'cotavel'        => $cotavel,
                'num_cotas'      => $cotavel === 'S' ? $numCotas : null,
                'cotas_vendidas' => 0,
                'vendido'        => 'N',
                'foto'           => null,
                'categoria'      => $categoria,
                'favorito'       => $favorito,
                'ativo'          => $valor > 0 ? 'S' : 'N',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            $count++;
        }

        $this->command->info("Presentes inseridos: {$count}");
    }

    private function normalizeGuestName(string $name): string
    {
        $name = $this->stripGuestTitles($name);
        $name = $this->removeParenthesesContent($name);
        $name = $this->normalizeAccents($name);
        $name = mb_strtolower(trim($name));
        $name = preg_replace('/[^a-z0-9\s]/', ' ', $name) ?? $name;
        return preg_replace('/\s+/', ' ', $name) ?? $name;
    }

    private function parseDelegacaoLine(string $line): array
    {
        $parts = array_map('trim', explode(',', $line));

        // Formato com IDs + nomes para conferência:
        // id_responsavel, id_dependente, Nome Responsável, Nome Dependente
        if (count($parts) >= 2 && ctype_digit($parts[0]) && ctype_digit($parts[1])) {
            return [$parts[0], $parts[1]];
        }

        // Formato padrão por vírgula: Responsável, Dependente
        if (count($parts) >= 2) {
            return [$parts[0], $parts[1]];
        }

        // Formato livre: Responsável para Dependente
        if (preg_match('/^(.+?)\s+para\s+(.+)$/iu', $line, $matches)) {
            return [trim($matches[1]), trim($matches[2])];
        }

        return ['', ''];
    }

    private function findGuestIdsByReference(string $reference, $guests, array $guestIndex): array
    {
        $ref = trim($reference);
        if ($ref === '') {
            return [];
        }

        // Permite usar ID diretamente no arquivo.
        if (ctype_digit($ref)) {
            return [(int) $ref];
        }

        $normalizedRef = $this->normalizeGuestName($ref);
        if (isset($guestIndex[$normalizedRef])) {
            return $guestIndex[$normalizedRef];
        }

        // Fallback: procura por inclusão parcial (apelidos), desde que o resultado seja único.
        $matched = [];
        foreach ($guests as $guest) {
            $guestNormalized = $this->normalizeGuestName($guest->nome_completo);
            if (
                str_contains($guestNormalized, $normalizedRef)
                || str_contains($normalizedRef, $guestNormalized)
            ) {
                $matched[] = $guest->id;
            }
        }

        return array_values(array_unique($matched));
    }

    private function normalizeAccents(string $value): string
    {
        $normalized = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
        return $normalized !== false ? $normalized : $value;
    }

    private function removeParenthesesContent(string $value): string
    {
        return preg_replace('/\(.*?\)/', ' ', $value) ?? $value;
    }

    private function stripGuestTitles(string $value): string
    {
        $value = preg_replace('/\b(tio|tia|dona|dom|sr\.?|sra\.?)\b/iu', ' ', $value) ?? $value;
        $value = preg_replace('/\b(v[oôó])\b/iu', ' ', $value) ?? $value;
        return preg_replace('/\b(a?v[oôó])\b/iu', ' ', $value) ?? $value;
    }

    private function findGuestByName(string $name): ?object
    {
        $target = $this->normalizeGuestName($name);
        return DB::table('guests')
            ->get()
            ->first(fn ($guest) => $this->normalizeGuestName($guest->nome_completo) === $target);
    }
}
