<div class="space-y-3">
    <div class="p-3 bg-slate-900/90 border {{ $node->parent_id == null ? 'border-amber-500/50 shadow-amber-950/20' : 'border-slate-700/70' }} rounded-xl flex flex-col md:flex-row items-center justify-between gap-3 shadow-md hover:border-indigo-500/60 transition-all">
        <div class="flex items-center gap-3 w-full md:w-auto">
            <img src="{{ $node->image_url ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop' }}" 
                 class="w-10 h-10 rounded-xl object-cover border border-slate-700 flex-shrink-0">
            <div>
                <div class="text-xs font-bold text-white flex items-center gap-2">
                    {{ $node->title }}
                    @if($node->line_type === 'coordination')
                        <span class="px-1.5 py-0.5 bg-purple-500/20 text-purple-300 border border-purple-500/30 rounded text-[9px]">Garis Koordinasi</span>
                    @else
                        <span class="px-1.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded text-[9px]">Garis Komando</span>
                    @endif
                </div>
                <div class="text-[11px] text-slate-300">{{ $node->name }}</div>
                @if($node->nip)
                    <div class="text-[9px] text-slate-500">NIP. {{ $node->nip }}</div>
                @endif
            </div>
        </div>

        <!-- Form Quick Pindah Atasan -->
        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
            <form action="{{ route('admin.org_chart.quick_move', $node->id) }}" method="POST" class="flex items-center gap-1.5">
                @csrf
                <select name="parent_id" onchange="this.form.submit()" 
                        class="px-2.5 py-1 bg-slate-800 border border-slate-700 rounded-lg text-[10px] text-slate-300 focus:outline-none focus:border-amber-400">
                    <option value="">-- Puncak Hirarki --</option>
                    @foreach($allNodes as $nItem)
                        @if($nItem->id != $node->id)
                            <option value="{{ $nItem->id }}" {{ $node->parent_id == $nItem->id ? 'selected' : '' }}>
                                &rarr; {{ $nItem->title }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @php
        $childrenNodes = isset($node->allChildren) && count($node->allChildren) > 0 ? $node->allChildren : $node->children;
    @endphp

    @if($childrenNodes && count($childrenNodes) > 0)
        <div class="pl-4 md:pl-6 border-l-2 {{ $node->line_type === 'coordination' ? 'border-dashed border-purple-500/40' : 'border-indigo-500/40' }} space-y-3 ml-3">
            @foreach($childrenNodes as $child)
                @include('admin.partials.org_tree_node', ['node' => $child, 'allNodes' => $allNodes])
            @endforeach
        </div>
    @endif
</div>
