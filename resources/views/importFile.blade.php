<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('file.store')}}" method="post" enctype="multipart/form-data" class="me-2">
        @csrf
        <div>
            <label for="controle">controle</label>
            <input type="text" name="controle">
          </div>
          <div>
            <label for="referencia">referencia</label>
            <input type="text" name="referencia">
        </div>
          <div>
            <label for="fechamento">fechamento</label>
            <input type="text" name="fechamento">
        </div>
        <div class="">
          <input type="file" class="form-control" name="inputFile" id="">
        </div>
        <div class="mt-3">
          <button type="submit" class="btn btn-success me-3">
            Confirmar
          </button>
        </div>
      </form>
</body>
</html>