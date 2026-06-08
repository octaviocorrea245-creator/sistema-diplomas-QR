<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Departamento</title>
</head>
<body>
    <h1>Editar Departamento</h1>

    @if($errors->any())
        <p style="color: red;">{{ $errors->first('name') }}</p>
    @endif

    <form action="{{ route('supervisor.departamentos.update', $departamento->id) }}" method="POST">
        @csrf
        @method('PUT') <label for="name">Nombre del Departamento:</label>
        <input type="text" name="name" id="name" value="{{ $departamento->name }}" required>
        
        <button type="submit">Actualizar</button>
        <a href="{{ route('supervisor.departamentos.index') }}">Cancelar</a>
    </form>
</body>
</html>