<div>

    <div id="music-list" class="articles">
        @forelse($allMusic as $music)
            <div wire:key="{{ $music->id }}" class="article-card">
                <div class="cover">
                    <img src="{{ asset("storage/$music->image") }}" alt="Article 1 Cover Image">
                    @if ($music->amount == 0)
                        <div class="overlay">
                            <a href="#" class="play-button track-list"
                                data-track="{{ asset("storage/$music->file") }}"
                                data-poster="{{ asset("storage/$music->image") }}"
                                data-title="{{ $music->title ?? '-' }}" data-singer="{{ $music->artist ?? '-' }}">
                                <i class="icon-play"></i>
                            </a>
                        </div>
                    @else
                        {{-- <div class="overlay">
                            <a href="#" class="play-button track-list"
                                data-track="{{ asset("storage/demos/$music->demo") }}"
                                data-poster="{{ asset("storage/$music->image") }}"
                                data-title="{{ $music->title ?? '-' }}" data-singer="{{ $music->artist ?? '-' }}">
                                <i class="icon-play"></i>
                            </a>
                        </div> --}}
                        <div class="overlay">
                            <div class="play-button track-list"
                                role="button"
                                tabindex="0"
                                data-track="{{ asset('storage/demos/'.$music->demo) }}"
                                data-poster="{{ asset('storage/'.$music->image) }}"
                                data-title="{{ $music->title ?? '-' }}"
                                data-singer="{{ $music->artist ?? '-' }}"
                                onclick="playTrack(this);">
                                <i class="icon-play"></i>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="details">
                    <h6 class="artist" id="artistName">{{ $music->artist ?? '-' }}</h6>

                    <p class="card-text" id="product1Description">
                        {{ $music->title ?? '-' }}
                    </p>

                    @if ($music->amount == 0)
                        <a href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}"
                            style="margin-right: 4px;" class="btn buy-button">Download</a>
                    @else
                        <a href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}"
                            style="margin-right: 4px;" class="btn buy-button">Buy R{{ $music->amount }}</a>
                    @endif
                    {{-- <button style="font-size: 9px; margin-right: 4px;"
                        class="btn btn-transparent btn-outline-danger btn-sm"
                        wire:click="incrementLikes({{ $music->id }})">
                        <span class="icon-heart"></span> {{ $music->likes }}
                    </button> --}}

                    <a style="font-size: 9px; " class="btn btn-transparent btn-outline-primary btn-sm"
                        href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}"><span
                            class="icon-eye"></span> {{ $music->views }}</a>
                </div>
                <?php
                $baseUrl = config('app.url');
                $url = "{$baseUrl}/msingle/{$music->slug}";
                $shareButtons = \Share::page($url, 'Check out this music: ' . $music->title)
                    ->facebook()
                    ->twitter()
                    ->whatsapp();
                ?>

                <div class="songs-button"><a href="#" class="dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><i class="icon-ellipsis-v"></i></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li>

                            <a class="dropdown-item"
                                href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}"><span
                                    class="icon-eye"></span> Views {{ $music->views }}</a>
                            <a class="dropdown-item"
                                href="{{ route('msingle.slug', ['slug' => urlencode($music->slug)]) }}"><span
                                    class="icon-download"></span>
                                Downloads {{ $music->downloads }}
                            </a>

                            <button class="dropdown-item" wire:click="incrementLikes({{ $music->id }})">
                                <span class="icon-heart"></span> React {{ $music->likes }}
                            </button>
                            <div class="dropdown-divider"></div>

                            {!! $shareButtons !!}
                        </li>
                    </ul>
                </div>
                <div class="details-left">

                    <p class="texts"><i class="icon-download"></i>&nbsp;{{ $music->downloads }}</p>
                    <p class="texts"><i class="icon-clock-o"></i>&nbsp;{{ $music->duration }}</p>
                </div>
            </div>
        @empty

            @lang('no_items_found')
        @endforelse

    </div>
    <div class="pagination">{{ $allMusic->links() }}</div>
</div>


