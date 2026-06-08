<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Departamento</title>
</head>
<body>
    <h1>Nuevo Departamento</h1>

    @if($errors->any())
        <p style="color: red;">{{ $errors->first('name') }}</p>
    @endif

    <form action="{{ route('supervisor.departamentos.store') }}" method="POST">
        @csrf
        <label for="name">Nombre del Departamento:</label>
        <input type="text" name="name" id="name" required>
        
        <button type="submit">Guardar</button>
        <a href="{{ route('supervisor.departamentos.index') }}">Cancelar</a>
    </form>
</body>
</html>