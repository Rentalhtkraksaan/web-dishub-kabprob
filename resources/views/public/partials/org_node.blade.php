<li>
    <div class="org-card-node {{ $node->parent_id == null ? 'org-card-root-node' : '' }}">
        @if($node->line_type === 'coordination')
            <span class="badge badge-warning text-dark font-weight-bold position-absolute" style="top: -10px; right: 10px; font-size: 0.65rem;">
                <i class="fas fa-link mr-1"></i> Koordinasi
            </span>
        @endif

        <img src="{{ $node->image_url ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&h=400&fit=crop' }}" 
             alt="{{ $node->name }}" class="org-node-avatar">
        
        <div class="org-node-title">{{ $node->title }}</div>
        <div class="org-node-name">{{ $node->name }}</div>
        
        @if($node->nip)
            <div class="org-node-nip">NIP. {{ $node->nip }}</div>
        @endif
    </div>

    @php
        $childNodes = isset($node->allChildren) && count($node->allChildren) > 0 ? $node->allChildren : $node->children;
    @endphp

    @if($childNodes && count($childNodes) > 0)
        <ul class="list-unstyled">
            @foreach($childNodes as $child)
                @include('public.partials.org_node', ['node' => $child])
            @endforeach
        </ul>
    @endif
</li>
