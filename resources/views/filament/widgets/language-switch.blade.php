<div class="flex space-x-2 p-2 gap-2">
    @php
        $current = session('locale', config('app.locale'));
    @endphp

    <a href="{{ route('filament.switch-language', ['lang' => 'en']) }}"
       class="px-3 py-1 border rounded {{ $current === 'en' ? 'bg-primary-500 text-white' : '' }}">
       English
    </a>

    <a href="{{ route('filament.switch-language', ['lang' => 'zh_CN']) }}"
       class="px-3 py-1 border rounded {{ $current === 'zh_CN' ? 'bg-primary-500 text-white' : '' }}">
       中文
    </a>
</div>
