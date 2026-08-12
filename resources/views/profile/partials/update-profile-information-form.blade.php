<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Información del perfil
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Actualiza tu información personal.
        </p>
    </header>

    {{-- Avatar --}}
    <div class="mt-6 flex items-center gap-5">
        <div style="width:64px;height:64px;border-radius:50%;overflow:hidden;flex-shrink:0;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700;color:#64748b;">
            @if($user->avatar_url)
                <img src="{{ $user->avatar_url }}" alt="Avatar" style="width:100%;height:100%;object-fit:cover;">
            @else
                {{ strtoupper(substr($user->full_name, 0, 1)) }}
            @endif
        </div>
        <div>
            <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="flex items-center gap-3 flex-wrap">
                @csrf
                <input type="file" name="avatar" accept="image/jpeg,image/png,image/webp" required
                       style="font-size:0.82rem;color:#475569;max-width:180px;">
                <button type="submit"
                        style="padding:0.4rem 0.85rem;border:none;border-radius:8px;background:#1A56B0;color:#fff;font-size:0.78rem;font-weight:600;cursor:pointer;">
                    Subir foto
                </button>
            </form>
            <p style="font-size:0.72rem;color:#94a3b8;margin:4px 0 0;">JPG, PNG o WebP · Máx 2 MB</p>
            @error('avatar')
                <p style="font-size:0.78rem;color:#DC2626;margin:4px 0 0;">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="full_name" :value="__('Nombre completo')" />
            <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full" :value="old('full_name', $user->full_name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('full_name')" />
        </div>

        <div>
            <x-input-label for="username" :value="__('Usuario')" />
            <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required />
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>

        <div>
            <x-input-label :value="__('Departamento')" />
            <x-text-input type="text" class="mt-1 block w-full bg-gray-100" :value="$user->department?->name ?? '—'" disabled />
        </div>

        <div>
            <x-input-label :value="__('Rol')" />
            <x-text-input type="text" class="mt-1 block w-full bg-gray-100"
                :value="$user->hasRole('admin') ? 'Administrador' : ($user->hasRole('supervisor') ? 'Supervisor' : ($user->hasRole('diseñador') ? 'Diseñador' : 'Beneficiario'))" disabled />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Guardado.') }}</p>
            @endif
        </div>
    </form>
</section>
