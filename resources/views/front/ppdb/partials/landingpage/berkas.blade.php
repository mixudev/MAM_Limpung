<section id="persyaratan" class="ppdb-section scroll-mt-[130px] py-16 bg-gray-50">
            <div class="max-w-6xl mx-auto px-6">
                
                <!-- Header -->
                <div class="text-center max-w-2xl mx-auto mb-12 fade-up-init">
                    <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2.5 py-1 rounded-sm uppercase tracking-wider">Persyaratan Dokumen</span>
                    <h2 class="text-2xl font-bold text-gray-900 mt-2.5 leading-tight">Berkas Yang Diperlukan</h2>
                    <p class="text-gray-500 mt-2 text-xs md:text-sm">Lengkapi berkas-berkas berikut untuk mempermudah panitia memverifikasi data pendaftaran Anda.</p>
                </div>

                <!-- Simple Table -->
                <div class="overflow-x-auto bg-white rounded-md shadow-sm border border-gray-200">
                    <table class="w-full text-left border-collapse min-w-[500px]">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="py-3 px-4 md:px-6 font-bold text-gray-900 text-xs md:text-sm">Nama Dokumen</th>
                                <th class="py-3 px-4 md:px-6 font-bold text-gray-900 text-xs md:text-sm">Keterangan</th>
                                <th class="py-3 px-4 md:px-6 font-bold text-gray-900 text-xs md:text-sm w-20">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($requirements as $req)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">{{ $req['label'] }}</td>
                                    <td class="py-3 px-4 md:px-6 text-xs text-gray-600">{{ $req['required'] ? 'Wajib disiapkan oleh semua siswa' : 'Opsional / sesuai program pilihan' }}</td>
                                    <td class="py-3 px-4 md:px-6 text-xs font-semibold">
                                        <span class="inline-block px-2 py-1 rounded text-white {{ $req['required'] ? 'bg-emerald-600' : 'bg-amber-500' }}">
                                            {{ $req['required'] ? 'Wajib' : 'Opsional' }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-8 px-6 text-center text-xs text-gray-400">
                                        Tidak ada persyaratan berkas yang dikonfigurasi.
                                    </td>
                                </tr>
                            @endforelse

                            <!-- Berkas Tambahan Section Header -->
                            <tr class="bg-blue-50 border-b border-blue-200">
                                <td colspan="3" class="py-3 px-4 md:px-6 text-xs font-bold text-blue-900 uppercase tracking-wide">Berkas Tambahan (Untuk Program Khusus / Beasiswa)</td>
                            </tr>

                            <!-- Item 1 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">Piagam / Sertifikat Prestasi</td>
                                <td class="py-3 px-4 md:px-6 text-xs text-gray-600">Untuk klaim Beasiswa Prestasi (Juara akademik/olahraga/kesenian min. Kabupaten)</td>
                                <td class="py-3 px-4 md:px-6 text-xs font-semibold"><span class="inline-block px-2 py-1 rounded text-white bg-blue-500">Opsional</span></td>
                            </tr>

                            <!-- Item 2 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">Kartu KIP, PKH, KPS, atau KKS</td>
                                <td class="py-3 px-4 md:px-6 text-xs text-gray-600">Untuk verifikasi Beasiswa Afirmasi (Keluarga kurang mampu)</td>
                                <td class="py-3 px-4 md:px-6 text-xs font-semibold"><span class="inline-block px-2 py-1 rounded text-white bg-blue-500">Opsional</span></td>
                            </tr>

                            <!-- Item 3 -->
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="py-3 px-4 md:px-6 text-xs md:text-sm font-semibold text-gray-900">Surat Rekomendasi Ranting Muhammadiyah</td>
                                <td class="py-3 px-4 md:px-6 text-xs text-gray-600">Untuk kader Muhammadiyah mendapat potongan biaya khusus</td>
                                <td class="py-3 px-4 md:px-6 text-xs font-semibold"><span class="inline-block px-2 py-1 rounded text-white bg-blue-500">Opsional</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Info Note -->
                <div class="mt-6 bg-blue-50 border border-blue-200 p-4 rounded-md flex items-start gap-3">
                    <span class="text-blue-900 font-bold text-xs md:text-sm leading-relaxed">
                        Catatan: Semua berkas diserahkan dalam map kertas warna Kuning (Putra) atau Merah (Putri).
                    </span>
                </div>
            </div>
        </section>