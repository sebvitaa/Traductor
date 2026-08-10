{{-- resources/views/traductor.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Traductor con IA</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 flex items-center justify-center p-6">

<div class="w-full max-w-xl space-y-5">
    @php $idiomas = ['ES'=>'Español','EN'=>'Inglés','FR'=>'Francés','RU'=>'Ruso','ZH'=>'Chino']; @endphp
  <h1 class="text-center text-3xl font-bold text-slate-900">Traductor con IA</h1>
  <form method="POST" action="/traducir"
        class="space-y-4 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
    @csrf
    <label for="idioma" class="block text-sm font-medium text-slate-700">Idioma</label>
    <select id="idioma" name="idioma"  class="w-full rounded-xl border border-slate-300 p-3 text-slate-900
         focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none">
        <option value="">-- Selecciona el idioma --</option>
        @foreach ($idiomas as $codigo => $nombre)
            <option value="{{ $codigo }}" @selected(old('idioma', 'EN') === $codigo)>{{ $nombre }}</option>
        @endforeach
    </select>

    @error('idioma')
    <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <textarea id="texto" name="texto" rows="4" placeholder="Escribe algo..."
      class="w-full rounded-xl border border-slate-300 p-3 text-slate-900
             focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none"
    >{{ old('texto') }}</textarea>

    @error('texto')
      <p class="text-sm text-red-600">{{ $message }}</p>
    @enderror

    <button type="submit"
      class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 font-medium text-white
             transition hover:bg-indigo-700">
      Traducir al inglés
    </button>
  </form>

  @isset($traduccion)
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
      <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Traducción</p>
      <p class="mt-2 text-lg text-slate-900">{{ $traduccion }}</p>
    </div>

    <script>
      console.log('Texto original:', @json($texto));
      console.log('Traducción:', @json($traduccion));
    </script>
  @endisset
</div>

</body>
</html>