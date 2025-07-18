<div class="flex items-center gap-3">
    <span class="flex items-center justify-center w-full h-8 rounded-md border border-[#bbb] shadow-sm" style="background: #{{ $getRecord()->productColor->hex_code ?? 'ccc' }};">
        <span class="font-semibold text-[#222] text-sm">{{ $text ?? '' }}</span>
    </span>
</div>