{{-- resources/views/traductor.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Traductor con IA</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root{ --acento:#7c6cff; --acento-2:#a855f7; }

    body.obsidiana{
      background:
        radial-gradient(900px 450px at 50% -10%, rgba(124,108,255,.14), transparent 60%),
        linear-gradient(180deg,#0a0b0f,#12141b) !important;
      color:#e6e8ee;
    }
    .obsidiana .bg-white{ background:rgba(23,26,34,.9) !important; }
    .obsidiana .ring-slate-200{ --tw-ring-color:#272c38 !important; }
    .obsidiana .text-slate-900{ color:#e6e8ee !important; }
    .obsidiana .text-slate-700{ color:#c7cddb !important; }
    .obsidiana .text-slate-400{ color:#8b93a7 !important; }
    .obsidiana .text-red-600{ color:#f87171 !important; }
    .obsidiana select,
    .obsidiana textarea{
      background:#0f1219 !important; border-color:#272c38 !important; color:#e6e8ee !important;
    }
    .obsidiana textarea::placeholder{ color:#5b6172 !important; }
    .obsidiana select:focus,
    .obsidiana textarea:focus{
      border-color:var(--acento) !important;
      box-shadow:0 0 0 3px rgba(124,108,255,.25) !important; outline:none !important;
    }
    .obsidiana .bg-indigo-600{
      background:linear-gradient(135deg,var(--acento),var(--acento-2)) !important;
      box-shadow:0 10px 25px -8px rgba(124,108,255,.6) !important;
    }
    .obsidiana .hover\:bg-indigo-700:hover{ background:linear-gradient(135deg,#6a5aff,#9333ea) !important; }
  </style>
</head>
<body class="obsidiana min-h-screen bg-slate-50 flex items-center justify-center p-6">
<div class="w-full max-w-xl space-y-5">
    @php $idiomas = ['ES'=>'Español','EN'=>'Inglés','FR'=>'Francés','RU'=>'Ruso','ZH'=>'Chino']; @endphp
    @php $funciones = ['TR'=>'Traducir','CO'=>'Corregir Ortografía']; @endphp
  <h1 class="text-center text-3xl font-bold text-slate-900">Traductor con IA</h1>
  <form method="POST" action="{{ route('traducir') }}"
        class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    @csrf
    <label for="funciones" class="block text-sm font-medium text-slate-700">Funciones</label>
    <select id="funciones" name="funciones"  class="w-full rounded-xl border border-slate-300 p-3 text-slate-900
         focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
        <option value="">-- Selecciona la función --</option>
        @foreach ($funciones as $codigo => $nombre)
            <option value="{{ $codigo }}" @selected(old('funciones', 'TR') === $codigo)>{{ $nombre }}</option>
        @endforeach
    </select>
    <div id="campoIdioma" class="mt-4 @if(old('funciones', 'TR') !== 'TR') hidden @endif">
        <label for="idioma" class="block text-sm font-medium text-slate-700">Idioma</label>
        <select id="idioma" name="idioma"  class="w-full rounded-xl border border-slate-300 p-3 text-slate-900
            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
            <option value="">-- Selecciona el idioma --</option>
            @foreach ($idiomas as $codigo => $nombre)
                <option value="{{ $codigo }}" @selected(old('idioma', 'EN') === $codigo)>{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    @error('idioma')
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <textarea id="texto" name="texto" rows="4" placeholder="Escribe algo..." maxlength="5000"
      class="w-full rounded-xl border border-slate-300 p-3 text-slate-900
             focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none"
    >{{ old('texto') }}</textarea>
    <p id="contador" class="text-right text-xs text-slate-400">0 / 5000</p>

    @error('texto')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <button type="submit"
      class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 font-medium text-white
             transition hover:bg-indigo-700">
      Enviar
    </button>
  </form>

    @if (session('traduccion'))
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">
        Traducción — {{ $idiomas[session('idioma')] ?? '' }}
        </p>
        <p class="mt-2 text-lg text-slate-900 whitespace-pre-wrap">{{ session('traduccion') }}</p>
    </div>
    @endif
<script>
    const texto = document.getElementById('texto');
    const contador = document.getElementById('contador');

    function actualizarContador() {
        contador.textContent = texto.value.length + ' / 5000';
    }

    texto.addEventListener('input', actualizarContador);
    actualizarContador(); // para que muestre bien el valor inicial (old('texto'))
</script>
</body>
<script>
    const funciones = document.getElementById('funciones');
    const campoIdioma = document.getElementById('campoIdioma');

    function alternarIdioma() {
        // Muestra el idioma solo cuando la función es Traducir (TR)
        campoIdioma.classList.toggle('hidden', funciones.value !== 'TR');
    }

    funciones.addEventListener('change', alternarIdioma);
    alternarIdioma();
</script>

</html>