sadhakjsdhjkashdkjas<div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Panel de Pruebas del Supervisor</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold text-lg text-indigo-600 mb-3">🛡️ Administradores</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('supervisor.admins.index') }}" class="text-blue-500 hover:underline block text-sm">
                        📁 Ver Lista (Index)
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.admins.create') }}" class="text-blue-500 hover:underline block text-sm">
                        ➕ Crear Nuevo (Create)
                    </a>
                </li>
                <li>
                    <span class="text-gray-400 text-xs block">
                        * Editar/Eliminar se prueban desde la lista.
                    </span>
                </li>
            </ul>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold text-lg text-green-600 mb-3">🎓 Alumnos</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('supervisor.alumnos.index') }}" class="text-blue-500 hover:underline block text-sm">
                        📁 Ver Lista (Index)
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.alumnos.show', 1) }}" class="text-blue-500 hover:underline block text-sm">
                        👁️ Ver Detalle Alumno ID: 1 (Show)
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold text-lg text-amber-600 mb-3">📚 Cursos</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('supervisor.cursos.index') }}" class="text-blue-500 hover:underline block text-sm">
                        📁 Ver Lista Cursos (Index)
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.cursos.show', 1) }}" class="text-blue-500 hover:underline block text-sm">
                        👁️ Ver Detalle Curso ID: 1 (Show)
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.cursos.alumnos', 1) }}" class="text-blue-500 hover:underline block text-sm font-medium text-amber-700">
                        👥 Alumnos del Curso ID: 1
                    </a>
                </li>
            </ul>
        </div>

        <div class="bg-white p-4 rounded-lg shadow">
            <h2 class="font-semibold text-lg text-purple-600 mb-3">🏢 Departamentos</h2>
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('supervisor.departamentos.index') }}" class="text-blue-500 hover:underline block text-sm">
                        📁 Ver Lista (Index)
                    </a>
                </li>
                <li>
                    <a href="{{ route('supervisor.departamentos.create') }}" class="text-blue-500 hover:underline block text-sm">
                        ➕ Crear Nuevo (Create)
                    </a>
                </li>
            </ul>
        </div>

    </div>
</div>