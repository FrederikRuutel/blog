 <div class="card bg-base-300 shadow-sm">
     @if ($post->images->count() === 1)
         <figure>
             <img src="{{ $post->images->first()->path }}" alt="{{ $post->title }}" />
         </figure>
     @elseif($post->images->count() > 1)
         <div class="carousel rounded-box">
            @foreach($post->images as $image)
                <div class="carousel-item w-full">
                    <img src="{{ $image->path }}" alt="{{ $post->title }}" class="w-full" />
                </div>
            @endforeach
         </div>
     @endif
     <div class="card-body">
         <h2 class="card-title">{{ $post->title }}</h2>
         @isset($posts)
             <p>{{ $post->snippet }}</p>
         @else
             <p>{!! $post->displayBody !!}</p>
         @endisset
         <span class="text-base-content/70">
             @if ($post->user)
                 <a href="{{ route('user', $post->user) }}" class="btn btn-link">{{ $post->user->name }}</a>
             @else
                 @lang('Unknown author')
             @endif
         </span>
         <span class="text-base-content/70">{{ $post->created_at?->diffForHumans() }}</span>
         <span class="text-base-content/70"><b>@lang('Comments:')</b> {{ $post->comments_count }}</span>
         <div class="text-base-content/70">
             <b>@lang('Likes:')</b>
             <div class="tooltip">
                 <div class="tooltip-content">
                     @foreach ($post->likedUsers as $liker)
                         <div>{{ $liker->user?->name ?? __('Unknown user') }}</div>
                     @endforeach
                 </div>
                 <span>{{ $post->likes_count ?? $post->likes()->count() }}</span>
             </div>
         </div>
         <div class="badge badge-secondary">{{ $post->category?->name ?? __('Uncategorized') }}</div>
         <div class="flex flex-wrap gap-2">
             @foreach ($post->tags as $tag)
                 <div class="badge badge-outline badge-primary">{{ $tag->name }}</div>
             @endforeach
         </div>
         <div class="card-actions justify-end">
             @auth
                 <form action="{{ route('like', $post) }}" method="POST">
                     @csrf
                     @if ($post->authHasLiked)
                         <button class="btn btn-error">@lang('Unlike')</button>
                     @else
                         <button class="btn btn-primary">@lang('Like')</button>
                     @endif
                 </form>
             @endauth
             @isset($posts)
                 <a href="{{ route('post', $post) }}" class="btn btn-primary">@lang('Read more')</a>
             @endisset
         </div>
     </div>
 </div>
