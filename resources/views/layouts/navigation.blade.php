<aside id="app-sidebar">

    {{-- ─── Placa de carrera (admins) / Logo UPGP (resto) ─── --}}
    @if(Auth::user()->hasRole('admin') && Auth::user()->department)

        {{-- PLACA: color sólido de carrera --}}
        <div style="background: {{ $brand['plate'] ?? $brand['color'] }}; padding: 1.1rem 1.25rem 1rem; position:relative; overflow:hidden;">
            {{-- Círculo decorativo sutil --}}
            <div style="position:absolute; right:-18px; top:-18px; width:80px; height:80px; border-radius:50%; background:rgba(255,255,255,0.07); pointer-events:none;"></div>
            <a href="{{ route('dashboard') }}" style="text-decoration:none; display:block; position:relative; z-index:1;">
                <div style="font-size:1.7rem; font-weight:900; color:#fff; letter-spacing:0.03em; line-height:1;">
                    {{ $brand['abbr'] ?? '' }}
                </div>
                <div style="font-size:0.65rem; color:rgba(255,255,255,0.65); margin-top:4px; line-height:1.4;">
                    {{ Auth::user()->department->name }}
                </div>
            </a>
        </div>

        {{-- Barra UPGP secundaria --}}
        <div style="display:flex; align-items:center; gap:9px; padding: 0.65rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.07);">
            <div style="width:24px; height:24px; border-radius:6px; background:rgba(255,255,255,0.08);
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.45)" stroke-width="2" style="width:13px;height:13px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5z"/>
                </svg>
            </div>
            <span style="font-size:0.72rem; color:rgba(255,255,255,0.35); font-weight:600; letter-spacing:0.07em;">UPGP</span>
        </div>

    @else

        {{-- Logo UPGP normal (supervisor / beneficiario) --}}
        <div style="border-bottom: 1px solid rgba(255,255,255,0.07); padding: 1.2rem 1.25rem;">
            <a href="{{ route('dashboard') }}" style="display:flex; align-items:center; gap:13px; text-decoration:none;">
                <div style="width:40px; height:40px; border-radius:11px; background: var(--brand);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:21px;height:21px;">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41
                                 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493
                                 a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489
                                 a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675
                                 A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:1.05rem; color:#fff; font-weight:800; letter-spacing:0.03em; line-height:1.1;">UPGP</div>
                    <div style="font-size:0.67rem; color:#4A6585; font-weight:500; margin-top:2px;">Universidad Politécnica</div>
                </div>
            </a>
        </div>

    @endif

    {{-- ─── Navigation links ─── --}}
    <nav style="flex:1; padding: 1rem 0.75rem; display:flex; flex-direction:column; gap:2px;">

        <div style="font-size:0.65rem; font-weight:600; color:#4A6585; letter-spacing:0.1em; text-transform:uppercase; padding:0.5rem 0.6rem 0.25rem;">Menú</div>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
            </svg>
            Dashboard
        </a>

        @role('supervisor')
        <div style="font-size:0.65rem; font-weight:600; color:#4A6585; letter-spacing:0.1em; text-transform:uppercase; padding:0.75rem 0.6rem 0.25rem; margin-top:0.25rem;">Supervisor</div>

        <a href="{{ route('supervisor.admins.index') }}" class="nav-link {{ request()->routeIs('supervisor.admins.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
            Administradores
        </a>

        <a href="{{ route('supervisor.cursos.index') }}" class="nav-link {{ request()->routeIs('supervisor.cursos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
            Cursos
        </a>

        <a href="{{ route('supervisor.alumnos.index') }}" class="nav-link {{ request()->routeIs('supervisor.alumnos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
            </svg>
            Alumnos
        </a>

        <a href="{{ route('supervisor.departamentos.index') }}" class="nav-link {{ request()->routeIs('supervisor.departamentos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/>
            </svg>
            Departamentos
        </a>
        @endrole

        @role('admin')
        <div style="font-size:0.65rem; font-weight:600; color:#4A6585; letter-spacing:0.1em; text-transform:uppercase; padding:0.75rem 0.6rem 0.25rem; margin-top:0.25rem;">Mi Área</div>

        <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('admin.cursos.index') }}" class="nav-link {{ request()->routeIs('admin.cursos.*') && !request()->routeIs('admin.cursos.alumnos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
            </svg>
            Cursos
        </a>

        <a href="{{ route('admin.alumnos.index') }}" class="nav-link {{ request()->routeIs('admin.alumnos.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/>
            </svg>
            Alumnos
        </a>

        <a href="{{ route('admin.plantillas.index') }}" class="nav-link {{ request()->routeIs('admin.plantillas.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
            </svg>
            Plantillas (legacy)
        </a>

        <a href="{{ route('admin.templates.index') }}" class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
            </svg>
            Plantillas Diploma
        </a>

        <a href="{{ route('admin.diplomas.index') }}" class="nav-link {{ request()->routeIs('admin.diplomas.*') && !request()->routeIs('admin.diplomas.mass.*') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Diplomas
        </a>

        <a href="{{ route('admin.diplomas.mass.create') }}" class="nav-link {{ request()->routeIs('admin.diplomas.mass.*') ? 'active' : '' }}" style="padding-left: 2.5rem;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:14px;height:14px;flex-shrink:0;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Generación masiva
        </a>
        @endrole

    </nav>

    {{-- ─── Usuario + logout ─── --}}
    <div style="padding: 1rem 1.25rem; border-top: 1px solid rgba(255,255,255,0.07);">
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:0.75rem;">
            <div style="width:36px; height:36px; border-radius:50%; background: var(--brand);
                        display:flex; align-items:center; justify-content:center;
                        font-size:0.8rem; font-weight:700; color:#fff; flex-shrink:0; transition:background 0.3s;">
                {{ strtoupper(substr(Auth::user()->full_name, 0, 1)) }}
            </div>
            <div style="min-width:0;">
                <div style="font-size:0.825rem; font-weight:600; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                    {{ Auth::user()->full_name }}
                </div>
                <div style="font-size:0.7rem; color: var(--brand-light); transition:color 0.3s;">
                    @if(Auth::user()->hasRole('supervisor')) Supervisor
                    @elseif(Auth::user()->hasRole('admin')) Administrador
                    @else Beneficiario
                    @endif
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    style="width:100%; display:flex; align-items:center; gap:8px; padding:0.55rem 0.75rem;
                           border-radius:8px; background:rgba(192,57,43,0.15); border:1px solid rgba(192,57,43,0.25);
                           color:#FCA5A5; font-size:0.8rem; font-weight:500; cursor:pointer; transition:background 0.15s;"
                    onmouseover="this.style.background='rgba(192,57,43,0.28)'"
                    onmouseout="this.style.background='rgba(192,57,43,0.15)'">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                </svg>
                Cerrar sesión
            </button>
        </form>
    </div>

</aside>

{{-- Mobile top bar --}}
<div style="display:none; position:fixed; top:0; left:0; right:0; height:56px; z-index:98;
            background:#0D1B35; border-bottom:1px solid rgba(255,255,255,0.1);
            align-items:center; padding:0 1rem; gap:12px;" id="mobile-topbar">
    <button onclick="openSidebar()"
            style="background:rgba(255,255,255,0.08); border:none; border-radius:8px;
                   width:36px; height:36px; display:flex; align-items:center; justify-content:center; cursor:pointer;">
        <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" style="width:20px;height:20px;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>
    <span style="color:#fff; font-size:0.9rem; font-weight:600;">Diplomas QR</span>
</div>

<style>
    @media (max-width: 768px) {
        #mobile-topbar { display: flex !important; }
        #app-main { padding-top: 56px; }
        #app-main > main { padding-top: 1.25rem; }
    }
</style>
