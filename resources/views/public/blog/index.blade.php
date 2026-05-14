<x-layouts.app title="Blog">
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-8 text-sm" aria-label="Breadcrumb">
                <ol class="flex space-x-2">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-900">Blog</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Blog</h1>
                <p class="mt-2 text-gray-600">Guide, consigli e novità dal mondo SkinTemple</p>
            </div>

            <!-- Content placeholder -->
            <div class="rounded-lg border border-gray-200 bg-white p-8 text-center">
                <p class="text-gray-600">Sezione in costruzione</p>
            </div>
        </div>
    </div>
</x-layouts.app>