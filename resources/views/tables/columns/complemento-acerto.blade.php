<div>
    <ul>
        @foreach ($getRecord()->valor_ajuda as $complemento)
            <li>{{$complemento->motivo}} - R$ {{$complemento->vlr_ajuda}}</li>
        @endforeach
    </ul>
</div>
