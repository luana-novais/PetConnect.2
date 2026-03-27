<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-gray-100 text-gray-900 h-screen overflow-hidden flex">

    <!-- Navbar Lateral (Sidebar) -->
    <nav class="w-64 bg-slate-900 border-r border-gray-200 text-white flex flex-col justify-between h-full">
        <div>
            <!-- Logo ou Título -->
            <div class="h-16 flex items-center justify-center border-b border-gray-700">
                <span class="text-2xl font-bold">PetConnect</span>
            </div>
            
            <!-- Links do Menu -->
            <ul class="flex flex-col mt-4 space-y-1 px-4">
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-800 rounded-md transition-colors">
                        Início
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-800 rounded-md transition-colors">
                        Meus Pets
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-800 rounded-md transition-colors">
                        Mensagens
                    </a>
                </li>
                <li>
                    <a href="#" class="block px-4 py-2 hover:bg-slate-800 rounded-md transition-colors">
                        Configurações
                    </a>
                </li>
            </ul>
        </div>

        <!-- Parte inferior da sidebar -->
        <div class="p-4 border-t border-gray-700">
            <a href="#" class="block px-4 py-2 text-center bg-red-600 hover:bg-red-700 rounded-md transition-colors">
                Sair
            </a>
        </div>
    </nav>

    <!-- Área de Conteúdo Principal -->
    <main class="flex-1 overflow-y-auto w-full">
        <!-- Um cabeçalho opcional da página -->
        <header class="bg-white shadow h-16 flex items-center px-6">
            <!-- Título dinâmico da página -->
            <h1 class="text-xl font-semibold text-gray-800">
                @yield('title', 'Página Atual')
            </h1>
        </header>

        <!-- Aqui o conteúdo das outras páginas é inserido -->
        <div class="p-6">
            @yield('content')
        </div>
    </main>

</body>
</html>