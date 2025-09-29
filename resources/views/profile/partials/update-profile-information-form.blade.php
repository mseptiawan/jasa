<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <link rel="icon"
          type="image/x-icon"
          href="{{ asset('logo-JasaReceh.ico') }}">

    <title>Edit Profil</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap"
          rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }

        .profile-photo-container {
            position: relative;
            width: 128px;
            height: 128px;
        }

        .profile-photo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
            border-radius: 9999px;
            cursor: pointer;
        }

        .profile-photo-container:hover .profile-photo-overlay {
            opacity: 1;
        }

        .profile-photo-overlay svg {
            width: 2rem;
            height: 2rem;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4 sm:p-6">
    <div class="w-full max-w-xl p-8 bg-white rounded-xl border border-gray-100">
        <header class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Edit Profil</h2>
            <p class="mt-2 text-sm text-gray-500">Ubah detail akun Anda, termasuk nama, email, bio, dan lainnya.</p>
        </header>

        <form id="send-verification"
              method="post"
              action="{{ route('verification.send') }}"></form>

        <form method="post"
              action="{{ route('profile.update') }}"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('patch')

            <div class="flex flex-col items-center mb-8">
                <div class="profile-photo-container">
                    @php $profilePhoto = $user->profile_photo ? asset('storage/'.$user->profile_photo) : 'https://www.gravatar.com/avatar/?d=mp&s=200'; @endphp
                    <img src="{{ $profilePhoto }}"
                         id="profilePhotoPreview"
                         alt="Foto Profil"
                         class="w-32 h-32 object-cover rounded-full border-4 border-gray-200">
                    <div class="profile-photo-overlay"
                         onclick="document.getElementById('profile_photo').click()">
                        <svg aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 20 18">
                            <path stroke="currentColor"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M10 12.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7Z" />
                            <path stroke="currentColor"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 3h-2l-.462-.924A2 2 0 0 0 12.793 1H7.207a2 2 0 0 0-1.745 1.076L5 3H3a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V5a2 2 0 0 0-2-2Z" />
                        </svg>
                    </div>
                </div>
                <input type="file"
                       name="profile_photo"
                       id="profile_photo"
                       class="hidden"
                       accept="image/*"
                       onchange="previewProfilePhoto(event)">
                <span class="mt-3 text-sm text-gray-400">Klik foto untuk mengunggah</span>
                <x-input-error class="mt-2 text-red-500"
                               :messages="$errors->get('profile_photo')" />
            </div>

            <div>
                <label for="full_name"
                       class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                <input id="full_name"
                       name="full_name"
                       type="text"
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       value="{{ old('full_name', $user->full_name) }}"
                       required
                       autofocus
                       autocomplete="name">
                <x-input-error class="mt-2"
                               :messages="$errors->get('full_name')" />
            </div>

            <div>
                <label for="email"
                       class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email"
                       name="email"
                       type="email"
                       class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       value="{{ old('email', $user->email) }}"
                       required
                       autocomplete="username">
                <x-input-error class="mt-2"
                               :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Email Anda belum terverifikasi.
                            <button form="send-verification"
                                    class="text-indigo-600 hover:text-indigo-900 font-medium">Klik di sini untuk
                                mengirim ulang email verifikasi.</button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                Tautan verifikasi baru telah dikirim ke alamat email Anda.
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <label for="bio"
                       class="block text-sm font-medium text-gray-700">Bio</label>
                <textarea id="bio"
                          name="bio"
                          rows="4"
                          class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('bio', $user->bio) }}</textarea>
                <x-input-error class="mt-2"
                               :messages="$errors->get('bio')" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="website"
                           class="block text-sm font-medium text-gray-700">Website</label>
                    <input id="website"
                           name="website"
                           type="url"
                           class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ old('website', $user->website) }}">
                    <x-input-error class="mt-2"
                                   :messages="$errors->get('website')" />
                </div>
                <div>
                    <label for="linkedin"
                           class="block text-sm font-medium text-gray-700">LinkedIn</label>
                    <input id="linkedin"
                           name="linkedin"
                           type="url"
                           class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ old('linkedin', $user->linkedin) }}">
                    <x-input-error class="mt-2"
                                   :messages="$errors->get('linkedin')" />
                </div>
                <div>
                    <label for="instagram"
                           class="block text-sm font-medium text-gray-700">Instagram</label>
                    <input id="instagram"
                           name="instagram"
                           type="url"
                           class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                           value="{{ old('instagram', $user->instagram) }}">
                    <x-input-error class="mt-2"
                                   :messages="$errors->get('instagram')" />
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Simpan Perubahan
                </button>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 2000)"
                       class="text-sm text-gray-500 transition-opacity duration-300">
                        Tersimpan.
                    </p>
                @endif
            </div>
        </form>
    </div>

    <script>
        function previewProfilePhoto(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById("profilePhotoPreview");
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>

</html>
