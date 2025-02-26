<?php

namespace App\Actions\Cotacao;

use App\Enums\StatusCotacaoEnum;
use App\Filament\Clusters\Cotacoes;
use App\Filament\Clusters\Cotacoes\Resources\CotacaoResource;
use App\Models\Cotacao;
use App\Models\ProdutoCotacao;
use App\Models\User;
use Filament\Notifications\Actions\Action;
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

        $msg = Auth::user()->name . ' adicionou o produto ' . $item->produto->descricao . ' na cotação ' . $item->cotacao->id;

        $this->notificationSucces($msg);

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
            ->body($body)
            ->actions([
                Action::make('Abrir')
                    ->button()
                    ->url(CotacaoResource::getUrl('edit', ['record' => $this->data['cotacao_id']]))
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase(User::find([4, 5]));
    }
}
