@if($entry->status == 'pending' && $entry->isFullyApproved())
    <a href="{{ url(config('backpack.base.route_prefix').'/order/'.$entry->id.'/approve') }}" 
       class="btn btn-sm btn-link" 
       data-toggle="tooltip" 
       title="Approve this order."
       onclick="return confirm('Are you sure you want to approve this order?')">
        <span class="text-success"><i class="la la-check"></i> Approve</span>
    </a>
@endif