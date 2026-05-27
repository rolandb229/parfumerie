<?php
/**
 * TATAVERNIS - Dashboard Admin Principal
 */

require_once '../config/config.php';
require_once '../classes/Auth.php';
require_once '../classes/Order.php';
require_once '../classes/Product.php';
require_once '../classes/Security.php';

$auth = Auth::getInstance();
$auth->requireAdmin();

$admin = $auth->user();
$orderModel = new Order();
$productModel = new Product();

// Statistiques
$orderStats = $orderModel->getStats();
$productStats = $productModel->getStats();
$recentOrders = $orderModel->getRecent(5);
$salesByDay = $orderModel->getSalesByDay(7);

$pageTitle = "Tableau de bord";
?>
<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Security::e($pageTitle) ?> - Admin TATAVERNIS</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#0B0B0B',
                        'gold': '#C8A96B',
                        'gold-light': '#D4B87A',
                        'cream': '#F8F5F0',
                        'muted': '#2C2C2C',
                        'muted-light': '#3C3C3C'
                    },
                    fontFamily: {
                        'display': ['Playfair Display', 'serif'],
                        'sans': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
    </style>
</head>
<body class="bg-primary text-cream min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-muted min-h-screen fixed left-0 top-0 z-40">
            <div class="p-6">
                <!-- Logo -->
                <a href="index.php" class="block mb-8">
                    <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/nouveau_logo_tata_vernis_copie%5B1%5D-MYSK5d7Sa5b4cQPhYsEszEKpB1EC51.png" alt="TATAVERNIS" class="h-10">
                </a>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-lg bg-gold text-primary font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        Tableau de bord
                    </a>
                    <a href="commandes.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        Commandes
                        <?php if ($orderStats['pending'] > 0): ?>
                            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full"><?= $orderStats['pending'] ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="produits.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Produits
                    </a>
                    <a href="categories.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Catégories
                    </a>
                    <a href="clients.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        Clients
                    </a>
                    <a href="avis.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        Avis
                    </a>
                    <a href="promotions.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Promotions
                    </a>
                    <a href="messages.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Messages
                    </a>
                    <a href="statistiques.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Statistiques
                    </a>
                    <a href="parametres.php" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-muted-light transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Paramètres
                    </a>

                    <hr class="my-4 border-muted-light">

                    <a href="../deconnexion.php" class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-400 hover:bg-red-500/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Déconnexion
                    </a>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar -->
            <header class="bg-muted border-b border-muted-light px-8 py-4 sticky top-0 z-30">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-display font-semibold">Tableau de bord</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        <!-- Search -->
                        <div class="relative">
                            <input type="text" placeholder="Rechercher..." class="bg-primary border border-muted-light rounded-lg px-4 py-2 pl-10 focus:outline-none focus:border-gold transition-colors w-64">
                            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2 hover:bg-muted-light rounded-lg transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                        </button>

                        <!-- User -->
                        <div class="flex items-center gap-3 pl-4 border-l border-muted-light">
                            <div class="w-10 h-10 bg-gold/20 rounded-full flex items-center justify-center">
                                <span class="text-gold font-semibold"><?= strtoupper(substr($admin['first_name'], 0, 1)) ?></span>
                            </div>
                            <div class="text-sm">
                                <p class="font-medium"><?= Security::e($admin['full_name']) ?></p>
                                <p class="text-gray-400 text-xs">Administrateur</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-muted rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gold/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="text-green-400 text-sm font-medium">+12.5%</span>
                        </div>
                        <p class="text-gray-400 text-sm mb-1">Ventes aujourd'hui</p>
                        <p class="text-2xl font-bold"><?= formatPrice($orderStats['revenue_today'] ?? 0) ?></p>
                    </div>

                    <div class="bg-muted rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <span class="text-green-400 text-sm font-medium">+8.7%</span>
                        </div>
                        <p class="text-gray-400 text-sm mb-1">Commandes</p>
                        <p class="text-2xl font-bold"><?= $orderStats['this_month'] ?? 0 ?></p>
                    </div>

                    <div class="bg-muted rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <span class="text-green-400 text-sm font-medium">+11.7%</span>
                        </div>
                        <p class="text-gray-400 text-sm mb-1">Clients</p>
                        <p class="text-2xl font-bold">1,240</p>
                    </div>

                    <div class="bg-muted rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <span class="text-green-400 text-sm font-medium">+2.3%</span>
                        </div>
                        <p class="text-gray-400 text-sm mb-1">Produits</p>
                        <p class="text-2xl font-bold"><?= $productStats['total'] ?? 86 ?></p>
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Sales Chart -->
                    <div class="lg:col-span-2 bg-muted rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-lg font-semibold">Evolution des ventes</h2>
                            <select class="bg-primary border border-muted-light rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-gold">
                                <option>7 derniers jours</option>
                                <option>30 derniers jours</option>
                                <option>Ce mois</option>
                            </select>
                        </div>
                        <canvas id="salesChart" height="300"></canvas>
                    </div>

                    <!-- Best Categories -->
                    <div class="bg-muted rounded-xl p-6">
                        <h2 class="text-lg font-semibold mb-6">Meilleures catégories</h2>
                        <div class="space-y-4">
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span>Parfums Femme</span>
                                    <span class="text-gold">40%</span>
                                </div>
                                <div class="h-2 bg-primary rounded-full">
                                    <div class="h-full bg-gold rounded-full" style="width: 40%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span>Parfums Homme</span>
                                    <span class="text-blue-400">30%</span>
                                </div>
                                <div class="h-2 bg-primary rounded-full">
                                    <div class="h-full bg-blue-400 rounded-full" style="width: 30%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span>Senteurs Unisexes</span>
                                    <span class="text-purple-400">20%</span>
                                </div>
                                <div class="h-2 bg-primary rounded-full">
                                    <div class="h-full bg-purple-400 rounded-full" style="width: 20%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex items-center justify-between text-sm mb-1">
                                    <span>Senteurs Maison</span>
                                    <span class="text-green-400">10%</span>
                                </div>
                                <div class="h-2 bg-primary rounded-full">
                                    <div class="h-full bg-green-400 rounded-full" style="width: 10%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="mt-8 bg-muted rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-semibold">Commandes récentes</h2>
                        <a href="commandes.php" class="text-gold text-sm hover:text-gold-light transition-colors">Voir tout</a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-sm text-gray-400 border-b border-muted-light">
                                    <th class="pb-4 font-medium">Commande</th>
                                    <th class="pb-4 font-medium">Client</th>
                                    <th class="pb-4 font-medium">Montant</th>
                                    <th class="pb-4 font-medium">Statut</th>
                                    <th class="pb-4 font-medium">Date</th>
                                    <th class="pb-4 font-medium"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-muted-light">
                                <?php 
                                $demoOrders = [
                                    ['order_number' => 'CMD-1255', 'first_name' => 'Awa', 'last_name' => 'Koné', 'total' => 120000, 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s')],
                                    ['order_number' => 'CMD-1254', 'first_name' => 'Yacine', 'last_name' => 'Diarra', 'total' => 80000, 'status' => 'confirmed', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
                                    ['order_number' => 'CMD-1253', 'first_name' => 'Mariam', 'last_name' => 'Traoré', 'total' => 99500, 'status' => 'shipped', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))],
                                    ['order_number' => 'CMD-1252', 'first_name' => 'Ismael', 'last_name' => 'Coulibaly', 'total' => 100000, 'status' => 'delivered', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 days'))],
                                    ['order_number' => 'CMD-1251', 'first_name' => 'Fatou', 'last_name' => 'Bamba', 'total' => 75000, 'status' => 'delivered', 'created_at' => date('Y-m-d H:i:s', strtotime('-4 days'))],
                                ];
                                $orders = !empty($recentOrders) ? $recentOrders : $demoOrders;
                                foreach ($orders as $order): 
                                ?>
                                <tr>
                                    <td class="py-4">
                                        <span class="font-medium">#<?= $order['order_number'] ?></span>
                                    </td>
                                    <td class="py-4">
                                        <?= Security::e($order['first_name'] . ' ' . $order['last_name']) ?>
                                    </td>
                                    <td class="py-4 font-medium"><?= formatPrice($order['total']) ?></td>
                                    <td class="py-4"><?= orderStatusBadge($order['status']) ?></td>
                                    <td class="py-4 text-gray-400"><?= formatDate($order['created_at']) ?></td>
                                    <td class="py-4">
                                        <a href="commande.php?id=<?= $order['id'] ?? '' ?>" class="text-gold hover:text-gold-light transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
    // Sales Chart
    const ctx = document.getElementById('salesChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            datasets: [{
                label: 'Ventes',
                data: [500000, 750000, 600000, 900000, 800000, 1200000, 950000],
                borderColor: '#C8A96B',
                backgroundColor: 'rgba(200, 169, 107, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#C8A96B',
                pointBorderColor: '#C8A96B',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#9CA3AF'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#9CA3AF',
                        callback: function(value) {
                            return (value / 1000) + 'K';
                        }
                    }
                }
            }
        }
    });
    </script>
</body>
</html>
