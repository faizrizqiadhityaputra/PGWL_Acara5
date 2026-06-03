<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-col gap-6 rounded-3xl p-4">
        <section class="rounded-3xl border border-neutral-200 bg-white/90 p-6 shadow-sm shadow-slate-200/50 dark:border-neutral-700 dark:bg-neutral-950/80 dark:shadow-none">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <p class="text-sm uppercase tracking-[0.3em] text-slate-500 dark:text-slate-400">Dashboard</p>
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950 dark:text-white">Ringkasan Sistem Web GIS</h1>
                    <p class="max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-400">Lihat performa data geospasial, aktivitas terbaru, dan insight penting secara cepat dari dashboard yang dirancang untuk pengguna profesional.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <button class="rounded-2xl border border-slate-200 bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:border-neutral-700 dark:bg-slate-200 dark:text-slate-950 dark:hover:bg-slate-300">Refresh</button>
                    <button class="rounded-2xl border border-sky-600 bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-700">Lihat Laporan</button>
                </div>
            </div>
        </section>

        <section class="grid gap-5 md:grid-cols-3">
            <article class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm shadow-slate-200/50 dark:border-neutral-700 dark:bg-neutral-950/80">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Objek</p>
                        <h2 class="mt-4 text-4xl font-semibold text-slate-950 dark:text-white">1.248</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">Jumlah data titik, garis, dan polygon yang tercatat.</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-sky-100 text-sky-700 dark:bg-sky-900/20 dark:text-sky-200">
                        <span class="text-2xl">📍</span>
                    </div>
                </div>
            </article>

            <article class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm shadow-slate-200/50 dark:border-neutral-700 dark:bg-neutral-950/80">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Aktivitas Hari Ini</p>
                        <h2 class="mt-4 text-4xl font-semibold text-slate-950 dark:text-white">24</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">Form input, update, dan aktivitas pengguna terbaru.</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-green-100 text-green-700 dark:bg-green-900/20 dark:text-green-200">
                        <span class="text-2xl">⚡</span>
                    </div>
                </div>
            </article>

            <article class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm shadow-slate-200/50 dark:border-neutral-700 dark:bg-neutral-950/80">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Sensor Kualitas</p>
                        <h2 class="mt-4 text-4xl font-semibold text-slate-950 dark:text-white">96%</h2>
                        <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">Status keandalan sistem dan koneksi data saat ini.</p>
                    </div>
                    <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-orange-100 text-orange-700 dark:bg-orange-900/20 dark:text-orange-200">
                        <span class="text-2xl">📈</span>
                    </div>
                </div>
            </article>
        </section>

        <section class="grid gap-5 xl:grid-cols-[2fr_1fr]">
            <article class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm shadow-slate-200/50 dark:border-neutral-700 dark:bg-neutral-950/80">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Analisis Peta</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Performa Data Geospasial</h2>
                    </div>
                    <button class="rounded-2xl border border-slate-200 bg-slate-950 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800 dark:border-neutral-700 dark:bg-slate-200 dark:text-slate-950 dark:hover:bg-slate-300">Filter Tampilan</button>
                </div>
                <div class="mt-6 min-h-[320px] rounded-3xl bg-slate-100 p-5 text-slate-500 dark:bg-neutral-900/80 dark:text-slate-400">
                    <div class="flex h-full flex-col items-center justify-center gap-4 text-center">
                        <p class="text-lg font-medium">Visualisasi peta dan grafik akan tampil di sini.</p>
                        <p class="max-w-md text-sm">Tambahkan grafik performa, heatmap, atau ringkasan geospasial untuk membantu tim mengambil keputusan lebih cepat.</p>
                    </div>
                </div>
            </article>

            <article class="rounded-3xl border border-neutral-200 bg-white p-6 shadow-sm shadow-slate-200/50 dark:border-neutral-700 dark:bg-neutral-950/80">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Aktivitas Terbaru</p>
                        <h2 class="mt-2 text-xl font-semibold text-slate-950 dark:text-white">Perubahan Data</h2>
                    </div>
                    <span class="rounded-2xl bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-700 dark:bg-neutral-900 dark:text-slate-300">4 item baru</span>
                </div>
                <div class="mt-6 space-y-4 text-sm text-slate-600 dark:text-slate-300">
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/80">
                        <p class="font-semibold text-slate-950 dark:text-white">Pengguna menambahkan titik baru</p>
                        <p class="mt-1">08:42 · 12 Mei 2026</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/80">
                        <p class="font-semibold text-slate-950 dark:text-white">Polygon berhasil diperbarui</p>
                        <p class="mt-1">07:30 · 12 Mei 2026</p>
                    </div>
                    <div class="rounded-3xl border border-slate-200 bg-slate-50 p-4 dark:border-neutral-700 dark:bg-neutral-900/80">
                        <p class="font-semibold text-slate-950 dark:text-white">Laporan perubahan struktur tersedia</p>
                        <p class="mt-1">06:05 · 12 Mei 2026</p>
                    </div>
                </div>
            </article>
        </section>
    </div>
</x-layouts::app>
