@extends('dashboard.layouts.main')

@section('content')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bc = document.getElementById('breadcrumb');
        if (bc) bc.textContent = 'Chatbot · Analitik';
    });
</script>

<div class="space-y-5">

    {{-- Header --}}
    @include('dashboard.admin.chatbot.partials._header', [
        'title' => 'Analitik',
        'subtitle' => 'Statistik penggunaan, trafik percakapan, dan umpan balik pengguna.'
    ])

    {{-- Flash --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-200 dark:border-emerald-800 text-sm text-emerald-700 dark:text-emerald-400 font-mono">
        <i class="fa-solid fa-circle-check shrink-0"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Content --}}
    <div class="bg-white dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 shadow-sm">
        @include('dashboard.admin.chatbot.partials._analytics')
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var trafficCtx = document.getElementById('trafficChart');
    if (trafficCtx) {
        new Chart(trafficCtx, {
            type: 'line',
            data: {
                labels: [@foreach($traffic as $t)'{{ \Carbon\Carbon::parse($t->date)->format("d M") }}',@endforeach],
                datasets: [{
                    label: 'Jumlah Chat',
                    data: [@foreach($traffic as $t){{ $t->count }},@endforeach],
                    borderColor: '#4f45b2', backgroundColor: 'rgba(79,69,178,0.07)',
                    borderWidth: 2, fill: true, tension: 0.4,
                    pointRadius: 4, pointBackgroundColor: '#4f45b2',
                    pointBorderColor: '#fff', pointBorderWidth: 2,
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    }


    // ── API Provider Daily Line Chart ────────────────────────────────────────
    var apiCtx = document.getElementById('apiProviderChart');
    if (apiCtx) {
        // Build last-7-days date labels
        var apiLabels = [];
        for (var d = 6; d >= 0; d--) {
            var dt = new Date();
            dt.setDate(dt.getDate() - d);
            apiLabels.push(dt.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }));
        }

        // Raw data from server: array of {date, api_key_used_id, count, provider, model}
        var rawDaily = @json($apiDailyRaw);

        var providerPalette = {
            'GEMINI':     { border: '#3b82f6', bg: 'rgba(59,130,246,0.10)' },
            'GROQ':       { border: '#f97316', bg: 'rgba(249,115,22,0.10)' },
            'DEEPSEEK':   { border: '#06b6d4', bg: 'rgba(6,182,212,0.10)' },
            'OPENROUTER': { border: '#8b5cf6', bg: 'rgba(139,92,246,0.10)' },
        };

        // Group by key_id -> build datasets
        var keyMap = {}; // key_id => {provider, model, dataByDate}
        rawDaily.forEach(function(row) {
            if (!keyMap[row.key_id]) {
                keyMap[row.key_id] = { provider: row.provider, model: row.model, byDate: {} };
            }
            keyMap[row.key_id].byDate[row.date] = row.count;
        });

        // Build ISO dates for last 7 days (for matching with server data)
        var isoDates = [];
        for (var i = 6; i >= 0; i--) {
            var dt2 = new Date();
            dt2.setDate(dt2.getDate() - i);
            isoDates.push(dt2.toISOString().split('T')[0]);
        }

        var datasets = [];
        var colorFallbacks = ['#4f45b2','#10b981','#f59e0b','#64748b','#ec4899'];
        var colorIdx = 0;
        Object.keys(keyMap).forEach(function(keyId) {
            var info    = keyMap[keyId];
            var palette = providerPalette[info.provider];
            var color   = palette ? palette.border : colorFallbacks[colorIdx++ % colorFallbacks.length];
            var bgColor = palette ? palette.bg : 'rgba(100,116,139,0.08)';
            var data    = isoDates.map(function(iso) { return info.byDate[iso] || 0; });
            datasets.push({
                label: info.provider + ' (' + info.model + ')',
                data: data,
                borderColor: color,
                backgroundColor: bgColor,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 4,
                pointBackgroundColor: color,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                _keyId: keyId,
            });
        });

        new Chart(apiCtx, {
            type: 'line',
            data: { labels: apiLabels, datasets: datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(ctx) {
                                return ' ' + ctx.dataset.label + ': ' + ctx.parsed.y + '×';
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { size: 10 } } },
                    y: { beginAtZero: true, ticks: { stepSize: 1, font: { size: 10 } }, grid: { color: 'rgba(148,163,184,0.12)' } }
                }
            }
        });

        // Build custom legend
        var legendEl = document.getElementById('apiProviderLegend');
        if (legendEl) {
            datasets.forEach(function(ds) {
                var item = document.createElement('div');
                item.className = 'flex items-center gap-1.5';
                item.innerHTML =
                    '<span style="width:20px;height:3px;background:' + ds.borderColor + ';border-radius:2px;display:inline-block;"></span>' +
                    '<span style="font-size:10px;font-family:monospace;color:#64748b">' + ds.label + '</span>';
                legendEl.appendChild(item);
            });
        }
    }
});
</script>
@endsection
