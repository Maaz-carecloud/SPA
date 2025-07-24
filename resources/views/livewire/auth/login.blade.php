<x-sections.guest>
    <div class="row w-100 h-100 m-0 shadow rounded-0 overflow-hidden" style="width:100vh;">
        <!-- Left: Image -->
        <div class="col-md-6 d-none d-md-block p-0">
            <img src="{{ asset('assets/images/BGS_login.svg') }}" alt="Login Side"
                class="img-fluid h-100 w-100 object-fit-cover" style="min-height:400px;object-fit:cover;">
        </div>
        <!-- Right: Login Form -->
        <div class="col-12 col-md-6 bg-light d-flex align-items-center justify-content-center" style="background: #fff2bf!important;">
            <div class="w-100 p-4 p-md-5 d-flex justify-content-center">
                <div style="max-width: 600px; width: 100%;">
                    <img src="{{ asset('assets/images/bgs-logo-monogram.webp') }}" class="img-fluid mb-3" style="max-width: 80px;" alt="Bagh Grammar School">
                    <h2 class="mb-2 fw-bold">Bagh Grammar School</h2>
                    <p class="mb-4 text-muted">Please login with your credentials!</p>
                    <form wire:submit.prevent="login">
                        <x-form.input 
                            label="Email address" 
                            name="login_input" 
                            id="login_input" 
                            model="login_input" 
                            placeholder="Email address" 
                            class="form-control-lg" 
                            required />
                        <x-form.input 
                            label="Password" 
                            name="password" 
                            id="password" 
                            type="password" 
                            model="password" 
                            placeholder="Password" 
                            class="form-control-lg" 
                            required />
                        <x-form.checkbox 
                            label="Remember me!" 
                            id="remember" 
                            name="remember" 
                            model="remember" />
                        <div class="d-grid mb-3 mt-3">
                            <button class="btn theme-filled-btn btn-lg" type="submit" wire:target="login" wire:loading.attr="disabled">
                                SIGN IN
                                <span wire:loading wire:target="login" class="align-middle ms-2">
                                    <span class="spinner-border spinner-border-sm text-light" role="status" aria-hidden="true"></span>
                                    <span class="visually-hidden">Loading...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-sections.guest>
@push('scripts')
<script>
    function prefillLoginForm() {
        // Find the Livewire component instance
        let lwInstance = null;
        if (window.Livewire && window.Livewire.components) {
            // Try to get the first Livewire component on the page
            const ids = Object.keys(window.Livewire.components);
            if (ids.length > 0) {
                lwInstance = window.Livewire.components[ids[0]];
            }
        }
        if (!lwInstance && window.Livewire) {
            // Fallback: try to find by DOM
            const el = document.querySelector('[wire\\:id]');
            if (el) {
                lwInstance = window.Livewire.find(el.getAttribute('wire:id'));
            }
        }
        if (!lwInstance) return;
        // Set Livewire properties from localStorage
        if (localStorage.getItem('remember_login_input')) {
            lwInstance.set('login_input', localStorage.getItem('remember_login_input'));
        }
        if (localStorage.getItem('remember_me') === 'true') {
            lwInstance.set('remember', true);
        }
    }
    document.addEventListener('livewire:initialized', function () {
        prefillLoginForm();
        // Save values to localStorage on form submit
        const form = document.querySelector('form');
        const loginInput = document.getElementById('login_input');
        const rememberCheckbox = document.getElementById('remember');
        if(form) {
            form.addEventListener('submit', function () {
                if (rememberCheckbox && rememberCheckbox.checked) {
                    localStorage.setItem('remember_login_input', loginInput ? loginInput.value : '');
                    localStorage.setItem('remember_me', 'true');
                } else {
                    localStorage.removeItem('remember_login_input');
                    localStorage.removeItem('remember_me');
                }
            });
        }
    });
</script>
@endpush