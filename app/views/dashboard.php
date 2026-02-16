<!DOCTYPE html>

<html lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>BNGRC Dashboard Overview</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#3b1e8a",
                        "background-light": "#f6f6f8",
                        "background-dark": "#161220",
                    },
                    fontFamily: {
                        "display": ["Public Sans"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased">
<!-- Layout Wrapper -->
<div class="flex min-h-screen">
<!-- Sidebar -->
<aside class="w-64 bg-primary text-white flex-shrink-0 flex flex-col fixed inset-y-0 left-0 z-50">
<div class="p-6 flex items-center gap-3">
<div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
<span class="material-icons text-primary text-2xl">security</span>
</div>
<div>
<h1 class="font-bold text-lg leading-tight uppercase tracking-wider">BNGRC</h1>
<p class="text-[10px] text-primary/60 font-medium opacity-80 uppercase">Gestion des Risques</p>
</div>
</div>
<nav class="flex-1 mt-6 px-4 space-y-2">
<a class="flex items-center gap-3 px-4 py-3 bg-white/10 rounded-lg border-l-4 border-white" href="#">
<span class="material-icons text-xl">dashboard</span>
<span class="font-medium text-sm">Dashboard</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-white/70 hover:bg-white/5 hover:text-white transition-all rounded-lg" href="#">
<span class="material-icons text-xl">location_city</span>
<span class="font-medium text-sm">Villes Impactées</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-white/70 hover:bg-white/5 hover:text-white transition-all rounded-lg" href="#">
<span class="material-icons text-xl">assignment_late</span>
<span class="font-medium text-sm">Besoins Recensés</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-white/70 hover:bg-white/5 hover:text-white transition-all rounded-lg" href="#">
<span class="material-icons text-xl">volunteer_activism</span>
<span class="font-medium text-sm">Gestion des Dons</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-white/70 hover:bg-white/5 hover:text-white transition-all rounded-lg" href="#">
<span class="material-icons text-xl">psychology</span>
<span class="font-medium text-sm">Simulations d'Impact</span>
</a>
</nav>
<div class="p-4 border-t border-white/10">
<div class="bg-white/5 rounded-xl p-4">
<p class="text-[11px] text-white/50 uppercase font-bold mb-2 tracking-widest">Utilisateur</p>
<div class="flex items-center gap-3">
<img alt="Admin" class="w-8 h-8 rounded-full border border-white/20" data-alt="User profile portrait of administrator" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_-BQEaeqadqYV_rHUDoRWHl5FlgJ7tGDeLrC-WjewY2YfXn8GJ7_hE0ifle9JTUd2Nx1aQh8nvxZmDHebyKjKiPkzE-8XjG5cFp91F_EUiM6wZ1P2ufP8REYOvBFvVskpWCLBNOnk2MGOTPDK9liL94-G4zSQE6Ym_qVbU2LKrWIMs2CGmwgVQOrhfAOxRMgBOa__mv9LEmRec2jusOGQS1AO5WrLu9yH5caOtT5J-RZk5joc3jJACj1JOQJtrrOFQLsc5ggFPXk"/>
<div class="overflow-hidden">
<p class="text-xs font-semibold truncate">Cdt. Rakotoarisoa</p>
<p class="text-[10px] text-white/60 truncate">Admin Central</p>
</div>
</div>
</div>
</div>
</aside>
<!-- Main Content Area -->
<main class="flex-1 ml-64 flex flex-col min-w-0">
<!-- Header -->
<header class="h-16 bg-white dark:bg-background-dark border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-8 sticky top-0 z-40">
<div class="flex items-center gap-4">
<h2 class="text-lg font-semibold text-slate-800 dark:text-slate-100">Système de Suivi des Dons</h2>
<span class="px-2 py-0.5 bg-primary/10 text-primary text-[10px] font-bold rounded uppercase tracking-wide">Live Updates</span>
</div>
<div class="flex items-center gap-6">
<div class="relative">
<span class="material-icons text-slate-400 hover:text-primary cursor-pointer">notifications</span>
<span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] flex items-center justify-center rounded-full border-2 border-white dark:border-background-dark">3</span>
</div>
<div class="h-8 w-[1px] bg-slate-200 dark:bg-slate-800"></div>
<div class="flex items-center gap-3 cursor-pointer group">
<span class="text-sm font-medium text-slate-600 dark:text-slate-400 group-hover:text-primary">Paramètres</span>
<span class="material-icons text-slate-400 group-hover:text-primary">settings</span>
</div>
</div>
</header>
<!-- Dashboard Content -->
<div class="p-8 space-y-8">
<!-- KPI Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Total Needs -->
<div class="bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
<span class="material-icons text-blue-600">inventory</span>
</div>
<span class="text-xs font-bold text-red-500">+12% vs hier</span>
</div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Total Besoins Estimés</p>
<h3 class="text-2xl font-bold mt-1">452,500 <span class="text-sm font-normal text-slate-400">Ar</span></h3>
</div>
<!-- Total Donations -->
<div class="bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center">
<span class="material-icons text-primary">volunteer_activism</span>
</div>
<span class="text-xs font-bold text-green-500">+8.4k aujourd'hui</span>
</div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Total Dons Reçus</p>
<h3 class="text-2xl font-bold mt-1">312,800 <span class="text-sm font-normal text-slate-400">Ar</span></h3>
</div>
<!-- Total Distributed -->
<div class="bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 bg-orange-50 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
<span class="material-icons text-orange-600">local_shipping</span>
</div>
<div class="flex -space-x-2">
<div class="w-6 h-6 rounded-full border-2 border-white bg-slate-200"></div>
<div class="w-6 h-6 rounded-full border-2 border-white bg-slate-300"></div>
</div>
</div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Dons Distribués</p>
<h3 class="text-2xl font-bold mt-1">210,150 <span class="text-sm font-normal text-slate-400">Ar</span></h3>
</div>
<!-- Satisfaction Rate -->
<div class="bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
<div class="flex items-center justify-between mb-4">
<div class="w-12 h-12 bg-green-50 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
<span class="material-icons text-green-600">check_circle</span>
</div>
<div class="w-16 h-1 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
<div class="w-2/3 h-full bg-green-500"></div>
</div>
</div>
<p class="text-slate-500 dark:text-slate-400 text-xs font-medium uppercase tracking-wider">Satisfaction Globale</p>
<h3 class="text-2xl font-bold mt-1">69.1<span class="text-sm font-normal text-slate-400">%</span></h3>
</div>
</div>
<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
<!-- Bar Chart Area (Needs vs Donations) -->
<div class="lg:col-span-2 bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
<div class="flex items-center justify-between mb-8">
<div>
<h4 class="font-bold text-slate-800 dark:text-slate-100">Comparaison Besoins vs Dons</h4>
<p class="text-xs text-slate-500">Visualisation par principales villes affectées</p>
</div>
<select class="text-xs border-slate-200 dark:border-slate-700 bg-transparent rounded-lg focus:ring-primary">
<option>Les 7 derniers jours</option>
<option>Ce mois-ci</option>
</select>
</div>
<!-- Simplified CSS Chart Representation -->
<div class="h-64 flex items-end justify-between px-4 gap-8">
<div class="flex-1 flex flex-col items-center gap-2 group">
<div class="w-full flex justify-center gap-1 items-end h-full">
<div class="w-4 bg-slate-200 dark:bg-slate-700 rounded-t h-[90%]"></div>
<div class="w-4 bg-primary rounded-t h-[65%]"></div>
</div>
<span class="text-[10px] font-medium text-slate-500">Antananarivo</span>
</div>
<div class="flex-1 flex flex-col items-center gap-2 group">
<div class="w-full flex justify-center gap-1 items-end h-full">
<div class="w-4 bg-slate-200 dark:bg-slate-700 rounded-t h-[70%]"></div>
<div class="w-4 bg-primary rounded-t h-[55%]"></div>
</div>
<span class="text-[10px] font-medium text-slate-500">Toamasina</span>
</div>
<div class="flex-1 flex flex-col items-center gap-2 group">
<div class="w-full flex justify-center gap-1 items-end h-full">
<div class="w-4 bg-slate-200 dark:bg-slate-700 rounded-t h-[85%]"></div>
<div class="w-4 bg-primary rounded-t h-[30%]"></div>
</div>
<span class="text-[10px] font-medium text-slate-500">Fianarantsoa</span>
</div>
<div class="flex-1 flex flex-col items-center gap-2 group">
<div class="w-full flex justify-center gap-1 items-end h-full">
<div class="w-4 bg-slate-200 dark:bg-slate-700 rounded-t h-[40%]"></div>
<div class="w-4 bg-primary rounded-t h-[38%]"></div>
</div>
<span class="text-[10px] font-medium text-slate-500">Mahajanga</span>
</div>
<div class="flex-1 flex flex-col items-center gap-2 group">
<div class="w-full flex justify-center gap-1 items-end h-full">
<div class="w-4 bg-slate-200 dark:bg-slate-700 rounded-t h-[60%]"></div>
<div class="w-4 bg-primary rounded-t h-[45%]"></div>
</div>
<span class="text-[10px] font-medium text-slate-500">Toliara</span>
</div>
</div>
<div class="mt-6 flex justify-center gap-6 border-t border-slate-100 dark:border-slate-800 pt-4">
<div class="flex items-center gap-2">
<div class="w-3 h-3 bg-slate-200 dark:bg-slate-700 rounded-sm"></div>
<span class="text-[10px] font-medium text-slate-500">Besoins Réels</span>
</div>
<div class="flex items-center gap-2">
<div class="w-3 h-3 bg-primary rounded-sm"></div>
<span class="text-[10px] font-medium text-slate-500">Dons Mobilisés</span>
</div>
</div>
</div>
<!-- Pie Chart Area (Donation Types) -->
<div class="bg-white dark:bg-background-dark p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col">
<h4 class="font-bold text-slate-800 dark:text-slate-100 mb-1">Typologie des Dons</h4>
<p class="text-xs text-slate-500 mb-8">Répartition par catégorie de ressources</p>
<div class="flex-1 flex items-center justify-center relative">
<!-- SVG Donut Chart -->
<svg class="w-40 h-40 transform -rotate-90">
<circle class="text-primary" cx="80" cy="80" fill="transparent" r="70" stroke="currentColor" stroke-dasharray="440" stroke-dashoffset="110" stroke-width="20"></circle>
<circle class="text-orange-400" cx="80" cy="80" fill="transparent" r="70" stroke="currentColor" stroke-dasharray="440" stroke-dashoffset="330" stroke-width="20"></circle>
<circle class="text-green-500" cx="80" cy="80" fill="transparent" r="70" stroke="currentColor" stroke-dasharray="440" stroke-dashoffset="400" stroke-width="20"></circle>
</svg>
<div class="absolute inset-0 flex flex-col items-center justify-center">
<span class="text-2xl font-bold">100%</span>
<span class="text-[10px] text-slate-400 uppercase">Données</span>
</div>
</div>
<div class="mt-8 space-y-3">
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-2 h-2 rounded-full bg-primary"></div>
<span class="text-xs text-slate-600 dark:text-slate-400">Financier (Espèces)</span>
</div>
<span class="text-xs font-bold">55%</span>
</div>
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-2 h-2 rounded-full bg-orange-400"></div>
<span class="text-xs text-slate-600 dark:text-slate-400">Matériels &amp; Logistique</span>
</div>
<span class="text-xs font-bold">30%</span>
</div>
<div class="flex items-center justify-between">
<div class="flex items-center gap-2">
<div class="w-2 h-2 rounded-full bg-green-500"></div>
<span class="text-xs text-slate-600 dark:text-slate-400">Dons en Nature (Vivres)</span>
</div>
<span class="text-xs font-bold">15%</span>
</div>
</div>
</div>
</div>
<!-- Data Table Section -->
<div class="bg-white dark:bg-background-dark rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
<div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
<h4 class="font-bold text-slate-800 dark:text-slate-100">Détails de Distribution par Ville</h4>
<div class="flex gap-2">
<div class="relative">
<span class="material-icons absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
<input class="pl-9 pr-4 py-2 text-xs border-slate-200 dark:border-slate-700 bg-background-light dark:bg-slate-800/50 rounded-lg focus:ring-primary w-64" placeholder="Rechercher une ville..." type="text"/>
</div>
<button class="flex items-center gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-lg text-xs font-bold hover:bg-slate-200 transition-colors">
<span class="material-icons text-sm">filter_list</span>
                                Filtres
                            </button>
</div>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left">
<thead class="bg-slate-50/50 dark:bg-slate-800/30 border-b border-slate-100 dark:border-slate-800">
<tr>
<th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ville / District</th>
<th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Besoins (Ar)</th>
<th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Dons Reçus</th>
<th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Reste à Combler</th>
<th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Satisfaction</th>
<th class="px-6 py-4 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100 dark:divide-slate-800">
<!-- Row 1 -->
<tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">AN</div>
<span class="text-sm font-semibold">Antananarivo</span>
</div>
</td>
<td class="px-6 py-4 text-sm">120,500,000</td>
<td class="px-6 py-4 text-sm">98,200,000</td>
<td class="px-6 py-4 text-sm text-slate-400">-22,300,000</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">81.5%</span>
</div>
</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 px-3 py-1 rounded text-xs font-bold transition-all">Consulter</button>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">TO</div>
<span class="text-sm font-semibold">Toamasina</span>
</div>
</td>
<td class="px-6 py-4 text-sm">85,200,000</td>
<td class="px-6 py-4 text-sm">42,600,000</td>
<td class="px-6 py-4 text-sm text-red-500 font-medium">-42,600,000</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">50.0%</span>
</div>
</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 px-3 py-1 rounded text-xs font-bold transition-all">Consulter</button>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">FI</div>
<span class="text-sm font-semibold">Fianarantsoa</span>
</div>
</td>
<td class="px-6 py-4 text-sm">94,000,000</td>
<td class="px-6 py-4 text-sm">22,500,000</td>
<td class="px-6 py-4 text-sm text-red-600 font-bold">-71,500,000</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">23.9%</span>
</div>
</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 px-3 py-1 rounded text-xs font-bold transition-all">Consulter</button>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">MA</div>
<span class="text-sm font-semibold">Mahajanga</span>
</div>
</td>
<td class="px-6 py-4 text-sm">45,000,000</td>
<td class="px-6 py-4 text-sm">42,000,000</td>
<td class="px-6 py-4 text-sm text-slate-400">-3,000,000</td>
<td class="px-6 py-4">
<div class="flex items-center gap-2">
<span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">93.3%</span>
</div>
</td>
<td class="px-6 py-4">
<button class="text-primary hover:bg-primary/10 px-3 py-1 rounded text-xs font-bold transition-all">Consulter</button>
</td>
</tr>
</tbody>
</table>
</div>
<div class="p-6 bg-slate-50/30 dark:bg-slate-800/20 flex items-center justify-between border-t border-slate-100 dark:border-slate-800">
<p class="text-[11px] text-slate-500">Affichage de 4 sur 22 districts</p>
<div class="flex gap-2">
<button class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-primary transition-colors">
<span class="material-icons text-sm">chevron_left</span>
</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-primary bg-primary text-white text-xs font-bold">1</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 text-xs font-bold hover:border-primary">2</button>
<button class="w-8 h-8 flex items-center justify-center rounded border border-slate-200 dark:border-slate-700 text-slate-400 hover:text-primary transition-colors">
<span class="material-icons text-sm">chevron_right</span>
</button>
</div>
</div>
</div>
</div>
</main>
</div>

```</body></html>