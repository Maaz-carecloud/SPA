<div class="header" x-data="{
    toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const contentWrapper = document.getElementById('contentWrapper');
        if (sidebar && contentWrapper) {
            sidebar.classList.toggle('collapsed');
            contentWrapper.classList.toggle('sidebar-collapsed');
        }
    }
}">
  <div class="d-flex gap-3 w-auto align-items-center">
    <span class="toggle-btn" id="toggleSidebar" x-on:click="toggleSidebar()">
      <i class="fas fa-bars"></i>
    </span>
  </div>
  <div class="d-flex align-items-center gap-3">
    <div class="align-items-center d-flex gap-2  header-card pe-3 ps-3 d-sm-none d-lg-block d-md-block">
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="18" viewBox="0 0 23 18" fill="none">
          <path
            d="M5.13422 12.481V5.57035C5.98738 5.37838 6.82433 5.24102 7.63143 4.99531C9.61504 4.39212 11.1482 3.13115 12.4561 1.56217C12.6568 1.32236 12.8737 1.09659 13.1053 0.886466C13.5746 0.459883 14.1138 0.395043 14.6854 0.652699C15.257 0.910356 15.6094 1.34803 15.6094 2.00753C15.6094 6.6715 15.6094 11.3338 15.6094 15.9943C15.6094 16.6342 15.2792 17.0625 14.7212 17.3295C14.136 17.6102 13.5729 17.5462 13.1002 17.112C12.7118 16.7368 12.3433 16.3415 11.9962 15.9278C10.2046 13.8913 7.97099 12.7361 5.25622 12.5254C5.21421 12.5146 5.17335 12.4997 5.13422 12.481Z"
            fill="black" />
          <path
            d="M4.10275 12.4528C3.45263 12.4528 2.83835 12.504 2.23602 12.4417C1.13202 12.3274 0.201222 11.2977 0.159417 10.1826C0.130978 9.40163 0.129552 8.62042 0.155147 7.83892C0.177765 7.25815 0.409559 6.70514 0.8078 6.28181C1.20604 5.85848 1.74389 5.59338 2.32219 5.53537C2.89893 5.48504 3.4825 5.52684 4.10275 5.52684V12.4528Z"
            fill="black" />
          <path
            d="M4.60348 13.4519C4.60348 14.4339 4.63419 15.3289 4.59324 16.2187C4.56082 16.9209 3.96361 17.4251 3.21367 17.478C2.53114 17.5266 1.77438 17.0693 1.71893 16.4141C1.63361 15.4201 1.6976 14.4143 1.6976 13.4519H4.60348Z"
            fill="black" />
          <path
            d="M20.1687 8.50269H21.5337C21.8656 8.50269 22.0934 8.63578 22.0943 8.99582C22.0951 9.35585 21.8699 9.49236 21.5372 9.49236C20.5845 9.49236 19.6318 9.49236 18.6791 9.49236C18.3378 9.49236 18.1356 9.33537 18.1441 8.98046C18.1527 8.63919 18.36 8.5078 18.6756 8.50695C19.1739 8.50183 19.6713 8.50269 20.1687 8.50269Z"
            fill="black" />
          <path
            d="M18.052 5.53708C17.9308 5.43726 17.7389 5.35621 17.7013 5.22909C17.6535 5.06272 17.6587 4.79824 17.7619 4.68477C18.2456 4.15069 18.7644 3.64732 19.2839 3.14907C19.4989 2.9426 19.7532 2.95028 19.9665 3.16272C20.1798 3.37516 20.1866 3.6294 19.9793 3.84525C19.4785 4.36483 18.9657 4.86905 18.4436 5.37327C18.3617 5.45006 18.2192 5.46883 18.052 5.53708Z"
            fill="black" />
          <path
            d="M18.0793 12.4366C18.2662 12.5492 18.4266 12.6073 18.54 12.7207C18.9999 13.161 19.4427 13.6183 19.8957 14.0662C20.144 14.311 20.2199 14.5704 19.9503 14.8391C19.6944 15.09 19.4384 15.0294 19.2081 14.7956C18.738 14.3264 18.2517 13.8699 17.8089 13.3751C17.692 13.2446 17.6485 12.9818 17.6852 12.8026C17.7133 12.6687 17.924 12.5731 18.0793 12.4366Z"
            fill="black" />
        </svg>
      </span>
      <p type="button" data-bs-toggle="modal" data-bs-target="#announcementModal">Announcements</p>
    </div>
    <div class="align-items-center d-flex gap-2 header-card pe-3 ps-3 d-sm-none d-lg-block d-md-block">
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
          <g clip-path="url(#clip0_3_443)">
            <path
              d="M12.532 11.394H5.69698C5.4475 10.569 5.43246 7.89375 5.67237 6.61356H12.5334C12.7472 8.19988 12.7467 9.80776 12.532 11.394Z"
              fill="black" />
            <path
              d="M5.86304 5.39693C6.18292 3.99712 6.60532 2.65951 7.51848 1.53993C8.54373 0.285706 9.64348 0.284339 10.6667 1.53583C11.5819 2.65609 12.0029 3.99643 12.3269 5.39693H5.86304Z"
              fill="black" />
            <path
              d="M5.86304 12.6079H12.3235C12.0405 13.8785 11.6482 15.0821 10.9121 16.147C9.76515 17.8066 8.43505 17.812 7.28404 16.1593C6.5438 15.0972 6.15353 13.8915 5.86304 12.6079Z"
              fill="black" />
            <path
              d="M4.40444 6.61834C4.36343 7.42282 4.29166 8.215 4.29166 9.00717C4.29166 9.79935 4.36548 10.5717 4.40854 11.3878C4.3108 11.396 4.21374 11.4117 4.11736 11.4117C2.99095 11.4117 1.86181 11.4076 0.737451 11.4179C0.509845 11.4179 0.395702 11.3694 0.341705 11.124C0.00963502 9.7237 0.0122098 8.26487 0.349222 6.86577C0.390233 6.69489 0.430559 6.5828 0.648596 6.58416C1.84336 6.59442 3.04085 6.58895 4.23288 6.591C4.29071 6.59539 4.34809 6.60454 4.40444 6.61834Z"
              fill="black" />
            <path
              d="M13.7821 11.4124C13.8245 10.5669 13.8963 9.77201 13.8963 8.98052C13.8963 8.19859 13.8245 7.41667 13.7814 6.58826H14.2216C15.3022 6.58826 16.3835 6.59237 17.4648 6.58826C17.6699 6.58826 17.7847 6.62039 17.8414 6.86166C18.1843 8.27121 18.1787 9.74306 17.825 11.1499C17.7991 11.2518 17.674 11.4035 17.594 11.4042C16.3329 11.4172 15.0719 11.4124 13.7821 11.4124Z"
              fill="black" />
            <path
              d="M17.2939 12.6044C16.7041 14.7349 13.2613 17.6152 11.1786 17.7027C11.3263 17.5332 11.463 17.3848 11.5888 17.229C12.6202 15.9611 13.1376 14.4656 13.4868 12.9004C13.5395 12.6673 13.6071 12.5805 13.8546 12.5833C14.9236 12.5969 15.9932 12.5887 17.0629 12.5901C17.1395 12.5921 17.2153 12.6044 17.2939 12.6044Z"
              fill="black" />
            <path
              d="M7.01067 0.295273C6.86166 0.466148 6.72633 0.6131 6.60057 0.768254C5.63683 1.95003 5.12489 3.33959 4.76605 4.79681C4.615 5.41196 4.62252 5.41196 3.99506 5.41196H0.891968C1.44697 3.29447 4.87609 0.426505 7.01067 0.295273Z"
              fill="black" />
            <path
              d="M17.2925 5.40991C16.0944 5.40991 14.9003 5.4147 13.711 5.39761C13.6392 5.39761 13.5292 5.22878 13.506 5.12421C13.2066 3.83376 12.8074 2.5809 12.0829 1.45927C11.8259 1.06079 11.517 0.695803 11.2381 0.323979C12.9222 0.122346 16.7806 3.33754 17.2925 5.40991Z"
              fill="black" />
            <path
              d="M7.00106 17.6924C5.0524 17.7081 1.5809 14.8217 0.883728 12.5915H1.93495C2.73123 12.5915 3.52751 12.5997 4.32311 12.586C4.56028 12.5819 4.6464 12.6489 4.7004 12.8922C5.04215 14.4581 5.56229 15.9536 6.59301 17.2222C6.72014 17.3787 6.85753 17.5257 7.00106 17.6924Z"
              fill="black" />
          </g>
          <defs>
            <clipPath id="clip0_3_443">
              <rect width="18" height="17.4095" fill="white" transform="translate(0.0942993 0.295273)" />
            </clipPath>
          </defs>
        </svg>
      </span>
      <p onclick="window.open('https://bgs.edu.pk', '_blank')" style="cursor:pointer;">Visit Website</p>
    </div>

    <div class="dropdown border-left">
      <button class="align-items-center btn d-flex dropdown-toggle gap-2 shadow-none outline-none border-0 border-start"
        type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <div>
          {{-- <img src="{{ asset('assets/images/user.png') }}" class="user-img" alt=""> --}}
          <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" class="user-img"
            alt="{{ auth()->user()->name }}">
        </div>
        <div class="d-sm-none d-lg-block d-md-block">
          <p class="strong-text">{{ auth()->user()->name }} <i class="fa-solid fa-angle-down ms-3"></i></p>
          {{-- <span class="d-block text-start w-100 light-text">{{ auth()->user()->username }}</span> --}}

        </div>

      </button>
      <ul class="dropdown-menu w-100">
        {{-- <li><a class="dropdown-item text-gary" href="#">Profile</a></li> --}}
        <li><a class="dropdown-item text-gary" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#passwordresetModal">Update
            Password</a></li>
        <li><a class="dropdown-item text-gary" href="{{ route('logout') }}">Logout</a></li>
      </ul>
    </div>
  </div>

  <x-modal id="passwordresetModal" title="Password Reset" action="Close" :is_edit="false" :is_not_crud="true">
    <livewire:update-password />
  </x-modal>

  <!-- Announcement Modal -->
  <x-modal id="announcementModal" title="Active Announcements" action="Close" :is_edit="false" :is_not_crud="true">
    <div style="max-height:90vh; overflow-y:auto;">
      @php
        $activeAnnouncements = \App\Models\Announcement::where('status', 'active')
          ->where(function($query) {
            $now = now();
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', $now);
          })
          ->orderByDesc('published_at')
          ->get();
      @endphp
      @forelse($activeAnnouncements as $announcement)
        <div class="mb-4 p-3 border shadow-sm rounded bg-white position-relative announcement-card">
          <div class="d-flex align-items-center mb-2">
            <span class="me-2 text-danger"><i class="fa fa-bullhorn fa-lg"></i></span>
            <h5 class="mb-0 fw-bold fs-6 text-danger">{{ $announcement->title }}</h5>
            <div class="d-flex gap-3 ms-3">
              @if($announcement->published_at)
                <span class="text-success small" style="font-size: 12px" title="Published Date"><i class="fa fa-calendar-alt me-1"></i> {{ $announcement->published_at->format('d M Y') }}</span>
              @endif
              @if($announcement->expires_at)
                <span class="text-danger small" style="font-size: 12px" title="Expiration Date"><i class="fa fa-clock me-1"></i> {{ $announcement->expires_at->format('d M Y') }}</span>
              @endif
              <span class="text-warning small" style="font-size: 12px" title="Author"><i class="fa fa-user me-1"></i> {{ $announcement->author }}</span>
            </div>
          </div>
          <div class="mb-2 text-dark announcement-content">{!! nl2br(e($announcement->content)) !!}</div>
          @if($announcement->link && $announcement->link != '#')
            <div class="mt-2">
              <a href="{{ $announcement->link }}" target="_blank" class="btn btn-sm theme-filled-btn px-3">
                <i class="fa fa-external-link-alt me-1"></i> Read More
              </a>
            </div>
          @endif
        </div>
      @empty
        <div class="text-center text-muted py-5">
          <i class="fa fa-bullhorn fa-2x mb-2"></i>
          <div>No active announcements.</div>
        </div>
      @endforelse
    </div>
  </x-modal>

</div>