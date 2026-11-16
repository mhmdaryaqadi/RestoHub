<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RestoHub — Meja {{ $table->table_number }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen">
    <div class="max-w-md mx-auto bg-white min-h-screen shadow-sm border-x border-slate-200">
        {{-- Header Meja --}}
        <header class="px-4 py-4 border-b border-slate-200 bg-white sticky top-0 z-10">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">
                        RestoHub • {{ $table->floor }}
                    </p>
                    <h1 class="text-lg font-bold text-slate-900">
                        Meja {{ $table->table_number }} — Katalog Menu
                    </h1>
                </div>
                <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">
                    Sesi Aktif
                </span>
            </div>
        </header>

        {{-- Daftar Kategori Ringkas --}}
        <div class="px-4 py-3 border-b border-slate-100 flex gap-2 overflow-x-auto">
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-900 text-white whitespace-nowrap">
                Semua ({{ $menus->count() }})
            </span>
            @foreach ($categories as $category)
                <span class="px-3 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 whitespace-nowrap">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>

        {{-- Daftar Menu dari Database MySQL --}}
        <main class="p-4 space-y-3">
            @foreach ($menus as $menu)
                <div class="p-3 rounded-xl border border-slate-200 flex justify-between items-start gap-3 {{ $menu->is_available ? 'bg-white' : 'bg-slate-50 opacity-60' }}">
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <h2 class="font-semibold text-sm text-slate-900">{{ $menu->name }}</h2>
                            @if ($menu->badge)
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded bg-amber-100 text-amber-800">
                                    {{ $menu->badge }}
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-1">{{ $menu->category->name }}</p>
                        @if ($menu->description)
                            <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $menu->description }}</p>
                        @endif
                        <p class="text-sm font-bold text-slate-900 mt-2">
                            Rp {{ number_format($menu->base_price, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="text-right">
                        @if ($menu->is_available)
                            <span class="inline-block px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-900 text-white">
                                Tersedia
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-1 text-xs font-medium rounded-lg bg-rose-100 text-rose-700">
                                Habis
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </main>
    </div>
</body>
</html>
