<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    /**
     * O site autentica por nome_completo + senha (não e-mail),
     * então o login do painel usa o mesmo par de credenciais.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNomeCompletoFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }

    protected function getNomeCompletoFormComponent(): Component
    {
        return TextInput::make('nome_completo')
            ->label('Nome completo')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'nome_completo' => $data['nome_completo'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.nome_completo' => __('filament-panel::pages/auth/login.messages.failed'),
        ]);
    }
}
