<?php

namespace App\Actions\Cotacao;

use App\Enums\StatusCotacaoEnum;
use App\Models\Cotacao;
use App\Models\ProdutoCotacao;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddItemCotacao
{

    protected   array           $data;

    public function __construct(Cotacao $cotacao, array $data)
    {
        $this->data                 = $data;
        $this->data['cotacao_id']   = $cotacao->id;
        $this->data['status']       = StatusCotacaoEnum::PENDENTE;

    }

    public function handle(): ProdutoCotacao|false
    {
        if (! $this->validate()) {
            return false;
        }
        
        $item = ProdutoCotacao::create($this->data);

        $this->notificationSucces();

        return $item;

    }

    private function validate(): bool
    {
        $validator = Validator::make($this->data, [
            'cotacao_id' => 'required|numeric',
            'produto_id' => 'required|numeric',
            'quantidade' => 'required|numeric|min:1',
        ]);
        
        if ($validator->fails()) {
            self::notificationFail("Erro de validação.");
            return false;
        }

        return true;
    }

    private function notificationFail(string $body = ''): void
    {
        Notification::make()
            ->color('danger')
            ->title('Falha durante solicitação!')
            ->body($body)
            ->send();
    }

    private function notificationSucces(string $body = ''): void
    {
        Notification::make()
            ->color('succes')
            ->title('Solicitação concluída!')
            ->body("Item adicionado com sucesso.")
            ->body(Auth::user()->name)
            ->sendToDatabase(User::find([4, 5]));
    }
}
