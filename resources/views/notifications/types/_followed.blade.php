<li class="media @if ( ! $loop->last) border-bottom @endif">
  <div class="media-left">
    <a href="{{ route('users.show', $notification->data['follower_id']) }}">
      <img class="media-object img-thumbnail mr-3" alt="{{ $notification->data['follower_name'] }}"
           src="{{ $notification->data['follower_avatar'] }}" style="width:48px;height:48px;"/>
    </a>
  </div>

  <div class="media-body">
    <div class="media-heading mt-0 mb-1 text-secondary">
      <a href="{{ route('users.show', $notification->data['follower_id']) }}">{{ $notification->data['follower_name'] }}</a>
       关注了你
      <span class="meta float-right" title="{{ $notification->created_at }}">
        <i class="far fa-clock"></i>
        {{ $notification->created_at->diffForHumans() }}
      </span>
    </div>
  </div>
</li>
