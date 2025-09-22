<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __("Informasi Profile") }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{
            __(
            "Edit informasi akun profil mu, termasuk bio, website, sosial media, dan foto profil."
            )
            }}
        </p>
    </header>

    <form id="send-verification"
          method="post"
          action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post"
          action="{{ route('profile.update') }}"
          class="mt-6 space-y-6"
          enctype="multipart/form-data">
        @csrf @method('patch')

        <!-- Nama lengkap -->
        <div>
            <x-input-label for="full_name"
                           :value="__('Nama lengkap')" />
            <x-text-input id="full_name"
                          name="full_name"
                          type="text"
                          class="mt-1 block w-full"
                          :value="old('full_name', $user->full_name)"
                          required
                          autofocus
                          autocomplete="name" />
            <x-input-error class="mt-2"
                           :messages="$errors->get('full_name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email"
                           :value="__('Email')" />
            <x-text-input id="email"
                          name="email"
                          type="email"
                          class="mt-1 block w-full"
                          :value="old('email', $user->email)"
                          required
                          autocomplete="username" />
            <x-input-error class="mt-2"
                           :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail &&
            ! $user->hasVerifiedEmail())
            <div>
                <p class="text-sm mt-2 text-gray-800">
                    {{ __("Email mu belum terverifikasi.") }}
                    <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        {{ __("klik disini untuk verifikasi ulang email.") }}
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                <p class="mt-2 font-medium text-sm text-green-600">
                    {{ __("Link verifikasi baru sudah dikirim ke email mu.") }}
                </p>
                @endif
            </div>
            @endif
        </div>

        <!-- Bio -->
        <div>
            <x-input-label for="bio"
                           :value="__('Bio')" />
            <textarea id="bio"
                      name="bio"
                      class="mt-1 block w-full rounded-md border-gray-300"
                      rows="3">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2"
                           :messages="$errors->get('bio')" />
        </div>

        <!-- Foto profil -->
        <div>
            <x-input-label for="profile_photo"
                           :value="__('Foto Profil')" />

            @php $profilePhoto = $user->profile_photo ?
            asset('storage/'.$user->profile_photo) : asset('images/profile-user.png');
            @endphp

            <!-- input file disembunyikan -->
            <input type="file"
                   name="profile_photo"
                   id="profile_photo"
                   class="hidden"
                   accept="image/*"
                   onchange="previewProfilePhoto(event)" />

            <!-- Gambar klikable -->
            <img src="{{ $profilePhoto }}"
                 id="profilePhotoPreview"
                 alt="Profile Photo"
                 class="mt-2 w-24 h-24 object-cover rounded-full border border-gray-300 cursor-pointer"
                 onclick="document.getElementById('profile_photo').click()" />

            <x-input-error class="mt-2"
                           :messages="$errors->get('profile_photo')" />
        </div>

        <!-- Website -->
        <div>
            <x-input-label for="website"
                           :value="__('Website')" />
            <x-text-input id="website"
                          name="website"
                          type="url"
                          class="mt-1 block w-full"
                          :value="old('website', $user->website)" />
            <x-input-error class="mt-2"
                           :messages="$errors->get('website')" />
        </div>

        <!-- LinkedIn -->
        <div>
            <x-input-label for="linkedin"
                           :value="__('LinkedIn')" />
            <x-text-input id="linkedin"
                          name="linkedin"
                          type="url"
                          class="mt-1 block w-full"
                          :value="old('linkedin', $user->linkedin)" />
            <x-input-error class="mt-2"
                           :messages="$errors->get('linkedin')" />
        </div>

        <!-- Instagram -->
        <div>
            <x-input-label for="instagram"
                           :value="__('Instagram')" />
            <x-text-input id="instagram"
                          name="instagram"
                          type="url"
                          class="mt-1 block w-full"
                          :value="old('instagram', $user->instagram)" />
            <x-input-error class="mt-2"
                           :messages="$errors->get('instagram')" />
        </div>

        <!-- Tombol simpan -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __("Simpan") }}</x-primary-button>

            @if (session('status') === 'profile-updated')
            <p x-data="{ show: true }"
               x-show="show"
               x-transition
               x-init="setTimeout(() => show = false, 2000)"
               class="text-sm text-gray-600">
                {{ __("Saved.") }}
            </p>
            @endif
        </div>
    </form>
</section>

<script>
    function previewProfilePhoto(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById("profilePhotoPreview");
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
