<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900 ">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 ">
            {{ __('Perbarui informasi profil akun dan alamat email Anda.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="bg-slate-50 p-4 rounded-xl border border-slate-200/60 flex flex-col sm:flex-row items-center gap-4"
            x-data="{ photoPreview: '{{ $user->profile_photo_url }}' }">
            <div class="relative group flex-shrink-0">
                <template x-if="photoPreview">
                    <img :src="photoPreview"
                        class="w-16 h-16 rounded-full object-cover border border-slate-200 ring-4 ring-blue-500/10 shadow-md">
                </template>
                <template x-if="!photoPreview">
                    <div
                        class="w-16 h-16 rounded-full bg-slate-200 border border-slate-200 flex items-center justify-center text-slate-400 text-xs font-bold uppercase tracking-wider">
                        Belum Ada Foto
                    </div>
                </template>
            </div>
            <div class="text-center sm:text-left space-y-1">
                <label
                    class="inline-block px-4 py-2 bg-white hover:bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-700 cursor-pointer shadow-sm transition-all hover:scale-[1.02] active:scale-[0.98]">
                    {{ __('Ubah Foto Profil') }}
                    <input type="file" name="foto"
                        @change="
                        const file = $event.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = (e) => { photoPreview = e.target.result; };
                            reader.readAsDataURL(file);
                        }
                    "
                        class="hidden" accept="image/*">
                </label>
                <p class="text-[10px] text-slate-400">Maksimal 2MB (JPG, JPEG, PNG, GIF)</p>
                <x-input-error class="mt-2" :messages="$errors->get('foto')" />
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 ">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 ">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan Perubahan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-semibold">Berhasil disimpan.</p>
            @endif
        </div>
    </form>
</section>
