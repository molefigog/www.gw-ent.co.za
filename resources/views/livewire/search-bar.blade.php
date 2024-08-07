<div>
    <div class="app-search d-none d-lg-block">
        <div class="position-relative">
            <input wire:model.live="search" type="text" class="form-control" placeholder="Search...">
            <span class="fa fa-search"></span>
        </div>
    </div>
{{--
    @if (sizeof($songs) > 0)
        <div class="col-lg">
            <div class="card">
                <div class="card-header">
                    Search
                </div>
                <div class="card-body">
                    @foreach ($songs as $song)
                        <a href="{{ route('msingle.slug', ['slug' => urlencode($song->slug)]) }}"
                            class="text-reset text-decoration-none">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar-xs">
                                        <img src="{{ asset("storage/$song->image") }}" class="rounded-circle"
                                            alt="{{ $song->image }}" style="width: 40px; height: 40px;" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold text-dark">{{ $song->title }}</span>
                                        <span class="font-size-12 text-muted">{{ $song->artist }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif --}}



    @if (sizeof($songs) > 0)

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0 show" aria-labelledby="page-header-notifications-dropdown" style="position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-20px, 72px);" data-popper-placement="bottom-end">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    {{-- <h5 class="m-0 font-size-16"> Notifications (258) </h5> --}}
                </div>
            </div>
        </div>
        <div data-simplebar="init" style="max-height: 230px;"><div class="simplebar-wrapper" style="margin: 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: -17px; bottom: 0px;"><div class="simplebar-content-wrapper" style="height: auto; overflow: hidden scroll;"><div class="simplebar-content" style="padding: 0px;">
            @foreach ($songs as $song)
            <a href="{{ route('msingle.slug', ['slug' => urlencode($song->slug)]) }}" class="text-reset notification-item">
                <div class="d-flex">
                    <div class="flex-shrink-0 me-3">
                        <div class="avatar-xs">
                            {{-- <span class="avatar-title bg-success rounded-circle font-size-16">
                                <i class="mdi mdi-cart-outline"></i>
                            </span> --}}
                            <img src="{{ asset("storage/$song->image") }}" class="rounded-circle"
                                            alt="{{ $song->image }}" style="width: 40px; height: 40px;" />
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">{{ $song->artist }}</h6>
                        <div class="font-size-12 text-muted">
                            <p class="mb-1">{{ $song->title }}</p>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div></div></div></div><div class="simplebar-placeholder" style="width: auto; height: 407px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: visible;"><div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: block; height: 129px;"></div></div></div>
        <div class="p-2 border-top">
            <div class="d-grid">
                <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                    View all
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
