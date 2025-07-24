<div class="d-flex min-vh-100 w-100">
    @livewire('offline')
    @include('partials.sidebar')
    <div class="content-wrapper" id="contentWrapper">
        @include('partials.header')
        <div class="Dashboard me-4 ms-4 mt-4" style="min-height: 88vh;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="bgs-card {{ !request()->is('dashboard') ? 'bgs-table-card' : '' }}">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>