<x-app-layout>
        <div class="container"
             style="max-width:700px;margin:2rem auto;">
                <h1>Apply Become Service Provider</h1>
                <p>Isi form untuk mendaftar menjadi penyedia jasa.</p>

                {{-- Pesan info --}}
                @if(session('status') || isset($status))
                <div class="alert alert-info">
                        {{ session('status') ?? $status }}
                </div>
                @endif

                {{-- Form hanya tampil kalau user belum daftar --}}
                @if(!session('status') && !isset($status))
                <form method="POST"
                      action="{{ route('service.apply.submit') }}"
                      enctype="multipart/form-data">
                        @csrf

                        <div class="form-group"
                             style="margin-bottom:1rem;">
                                <label for="phone_number">No. HP</label>
                                <input type="text"
                                       name="phone_number"
                                       id="phone_number"
                                       value="{{ old('phone_number') }}"
                                       class="form-control">
                                @error('phone_number')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group"
                             style="margin-bottom:1rem;">
                                <label for="address">Alamat</label>
                                <textarea name="address"
                                          id="address"
                                          rows="2"
                                          class="form-control">{{ old('address') }}</textarea>
                                @error('address')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group"
                             style="margin-bottom:1rem;">
                                <label for="id_card">Upload KTP / SIM</label>
                                <input type="file"
                                       name="id_card"
                                       id="id_card"
                                       class="form-control">
                                @error('id_card')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group"
                             style="margin-bottom:1rem;">
                                <label for="selfie">Upload Selfie dengan KTP</label>
                                <input type="file"
                                       name="selfie"
                                       id="selfie"
                                       class="form-control">
                                @error('selfie')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group"
                             style="margin-bottom:1rem;">
                                <label for="skills">Skill Utama</label>
                                <input type="text"
                                       name="skills"
                                       id="skills"
                                       value="{{ old('skills') }}"
                                       class="form-control">
                                @error('skills')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group mb-3">
                                <label for="experience">Pengalaman Kerja</label>
                                <select name="experience"
                                        id="experience"
                                        class="form-control">
                                        <option value="">-- Pilih Pengalaman --</option>
                                        <option value="0-1 tahun"
                                                {{
                                                old('experience')=='0-1 tahun'
                                                ? 'selected'
                                                : ''
                                                }}>0-1 tahun</option>
                                        <option value="1-3 tahun"
                                                {{
                                                old('experience')=='1-3 tahun'
                                                ? 'selected'
                                                : ''
                                                }}>1-3 tahun</option>
                                        <option value="3-5 tahun"
                                                {{
                                                old('experience')=='3-5 tahun'
                                                ? 'selected'
                                                : ''
                                                }}>3-5 tahun</option>
                                        <option value="5+ tahun"
                                                {{
                                                old('experience')=='5+ tahun'
                                                ? 'selected'
                                                : ''
                                                }}>5+ tahun</option>
                                </select>
                                @error('experience')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group mb-3">
                                <label for="portfolio">Link website Portfolio</label>
                                <input type="text"
                                       name="portfolio"
                                       id="portfolio"
                                       value="{{ old('portfolio') }}"
                                       class="form-control">
                                @error('portfolio')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group mb-3">
                                <label for="education">Pendidikan Terakhir</label>
                                <select name="education"
                                        id="education"
                                        class="form-control">
                                        <option value="">-- Pilih Pendidikan --</option>
                                        <option value="SD"
                                                {{
                                                old('education')=='SD'
                                                ? 'selected'
                                                : ''
                                                }}>SD</option>
                                        <option value="SMP"
                                                {{
                                                old('education')=='SMP'
                                                ? 'selected'
                                                : ''
                                                }}>SMP</option>
                                        <option value="SMA/SMK"
                                                {{
                                                old('education')=='SMA/SMK'
                                                ? 'selected'
                                                : ''
                                                }}>SMA/SMK</option>
                                        <option value="D3"
                                                {{
                                                old('education')=='D3'
                                                ? 'selected'
                                                : ''
                                                }}>D3</option>
                                        <option value="S1"
                                                {{
                                                old('education')=='S1'
                                                ? 'selected'
                                                : ''
                                                }}>S1</option>
                                        <option value="S2"
                                                {{
                                                old('education')=='S2'
                                                ? 'selected'
                                                : ''
                                                }}>S2</option>
                                        <option value="S3"
                                                {{
                                                old('education')=='S3'
                                                ? 'selected'
                                                : ''
                                                }}>S3</option>
                                </select>
                                @error('education')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <div class="form-group"
                             style="margin-bottom:1rem;">
                                <label for="cv">Upload CV (PDF/DOCX)</label>
                                <input type="file"
                                       name="cv"
                                       id="cv"
                                       class="form-control">
                                @error('cv')
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                                @enderror
                        </div>

                        <button type="submit"
                                class="btn">Kirim Pengajuan</button>
                </form>
                @endif
        </div>
</x-app-layout>
