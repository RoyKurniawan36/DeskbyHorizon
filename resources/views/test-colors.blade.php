<!DOCTYPE html>
<html lang="en" 
      x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches) }" 
      x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" 
      :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Color Test</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-background-100 p-8 text-text-900">
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Dark Mode Toggle -->
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-bold text-text-900 mb-8">Color System Test</h1>
            <button @click="darkMode = !darkMode" 
                    class="p-3 rounded-lg bg-background-200 text-text-800 hover:bg-background-300">
                <span x-show="!darkMode">🌙 Dark Mode</span>
                <span x-show="darkMode">☀️ Light Mode</span>
            </button>
        </div>
        
        <!-- Text Colors -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Text Colors</h2>
            <div class="space-y-2">
                <p class="text-text-50 bg-text-900 px-2 py-1 rounded">Text 50 (lightest)</p>
                <p class="text-text-100">Text 100</p>
                <p class="text-text-300">Text 300</p>
                <p class="text-text-500">Text 500</p>
                <p class="text-text-700">Text 700</p>
                <p class="text-text-900">Text 900 (darkest)</p>
            </div>
        </div>

        <!-- Background Colors -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Background Colors</h2>
            <div class="flex gap-2 flex-wrap">
                <div class="w-20 h-20 bg-background-50 rounded border-2 border-text-300 flex items-center justify-center text-xs">50</div>
                <div class="w-20 h-20 bg-background-100 rounded border-2 border-text-300 flex items-center justify-center text-xs">100</div>
                <div class="w-20 h-20 bg-background-300 rounded border-2 border-text-300 flex items-center justify-center text-xs">300</div>
                <div class="w-20 h-20 bg-background-500 rounded border-2 border-text-300 flex items-center justify-center text-xs text-text-50">500</div>
                <div class="w-20 h-20 bg-background-700 rounded border-2 border-text-300 flex items-center justify-center text-xs text-text-50">700</div>
                <div class="w-20 h-20 bg-background-900 rounded border-2 border-text-300 flex items-center justify-center text-xs text-text-50">900</div>
            </div>
        </div>

        <!-- Primary Colors -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Primary Colors</h2>
            <div class="flex gap-2 flex-wrap">
                <div class="w-20 h-20 bg-primary-50 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-primary-100 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-primary-300 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-primary-500 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-primary-700 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-primary-900 rounded border-2 border-text-300"></div>
            </div>
        </div>

        <!-- Secondary Colors -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Secondary Colors</h2>
            <div class="flex gap-2 flex-wrap">
                <div class="w-20 h-20 bg-secondary-50 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-secondary-100 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-secondary-300 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-secondary-500 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-secondary-700 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-secondary-900 rounded border-2 border-text-300"></div>
            </div>
        </div>

        <!-- Accent Colors -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Accent Colors</h2>
            <div class="flex gap-2 flex-wrap">
                <div class="w-20 h-20 bg-accent-50 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-accent-100 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-accent-300 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-accent-500 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-accent-700 rounded border-2 border-text-300"></div>
                <div class="w-20 h-20 bg-accent-900 rounded border-2 border-text-300"></div>
            </div>
        </div>

        <!-- Buttons -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Buttons</h2>
            <div class="flex gap-4 flex-wrap">
                <button class="bg-primary-600 hover:bg-primary-700 text-text-50 px-6 py-3 rounded-lg transition-colors">
                    Primary Button
                </button>
                <button class="bg-secondary-600 hover:bg-secondary-700 text-text-50 px-6 py-3 rounded-lg transition-colors">
                    Secondary Button
                </button>
                <button class="bg-accent-600 hover:bg-accent-700 text-text-50 px-6 py-3 rounded-lg transition-colors">
                    Accent Button
                </button>
            </div>
        </div>

        <!-- Cards -->
        <div class="bg-background-50 p-6 rounded-lg">
            <h2 class="text-2xl font-bold text-text-900 mb-4">Cards</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-background-100 p-4 rounded-lg border border-background-300">
                    <h3 class="text-lg font-bold text-primary-600 mb-2">Card Title</h3>
                    <p class="text-text-700">This is a card with background and border.</p>
                </div>
                <div class="bg-primary-50 p-4 rounded-lg">
                    <h3 class="text-lg font-bold text-primary-800 mb-2">Primary Card</h3>
                    <p class="text-text-700">This card uses primary colors.</p>
                </div>
                <div class="bg-accent-50 p-4 rounded-lg">
                    <h3 class="text-lg font-bold text-accent-800 mb-2">Accent Card</h3>
                    <p class="text-text-700">This card uses accent colors.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>