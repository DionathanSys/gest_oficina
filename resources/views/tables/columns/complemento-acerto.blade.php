<div>
    <ul>
        @foreach ($getRecord()->valor_ajuda as $complemento)
            <li>{{$getRecord()->getComplemento()}}</li>
        @endforeach
    </ul>
</div>
