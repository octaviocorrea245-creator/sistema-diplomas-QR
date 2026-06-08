<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Departamentos</title>
</head>
<body>
    <h1>Lista de Departamentos</h1>

    @if(session('success'))
        <p style="color: green;">{{ session('success') }}</p>
    @endif

    <p><a href="{{ route('supervisor.departamentos.create') }}">Agregar Nuevo Departamento</a></p>

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($departments as $dept)
                <tr>
                    <td>{{ $dept->id }}</td>
                    <td>{{ $dept->name }}</td>
                    <td>
                        <a href="{{ route('supervisor.departamentos.edit', $dept->id) }}">Editar</a>
                        
                        <form action="{{ route('supervisor.departamentos.destroy', $dept->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>