<!-- Data Table -->
<table>
    <thead>
        <tr>
            <th style="width: 25px;">No</th>
            @foreach($selectedFields as $f)
                <th>
                    @switch($f)
                        @case('nomor_registrasi') No. Registrasi @break
                        @case('nama_lengkap') Nama Lengkap @break
                        @case('nisn') NISN @break
                        @case('jenis_kelamin') L/P @break
                        @case('tempat_lahir') Tempat Lahir @break
                        @case('tanggal_lahir') Tgl Lahir @break
                        @case('sekolah_asal') Sekolah Asal @break
                        @case('ukuran_baju') Seragam @break
                        @case('nomor_hp') No. HP/WA @break
                        @case('email') Email @break
                        @case('alamat_lengkap') Alamat Rumah @break
                        @case('nama_ayah') Nama Ayah @break
                        @case('nama_ibu') Nama Ibu @break
                        @case('status') Status @break
                        @case('submitted_at') Tgl Daftar @break
                        @default
                            @php
                                $matched = collect($customFields)->firstWhere('id', $f);
                                $label = $matched ? $matched['label'] : strtoupper($f);
                                if ($f === 'nama_wali') {
                                    $label = 'Nama Wali';
                                }
                                echo $label;
                            @endphp
                    @endswitch
                </th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($students as $index => $student)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                @foreach($selectedFields as $f)
                    @switch($f)
                        @case('nomor_registrasi')
                            <td class="center font-mono" style="font-weight: bold;">{{ $student->nomor_registrasi }}</td>
                            @break
                        @case('nama_lengkap')
                            <td style="font-weight: bold; text-transform: uppercase;">{{ $student->nama_lengkap }}</td>
                            @break
                        @case('nisn')
                            <td class="center font-mono">{{ $student->nisn }}</td>
                            @break
                        @case('jenis_kelamin')
                            <td class="center">{{ $student->jenis_kelamin }}</td>
                            @break
                        @case('tempat_lahir')
                            <td>{{ $student->tempat_lahir }}</td>
                            @break
                        @case('tanggal_lahir')
                            <td class="center font-mono">{{ $student->tanggal_lahir?->format('d-m-Y') ?? '-' }}</td>
                            @break
                        @case('sekolah_asal')
                            <td style="text-transform: uppercase;">{{ $student->sekolah_asal }}</td>
                            @break
                        @case('ukuran_baju')
                            <td class="center font-mono" style="font-weight: bold;">{{ $student->ukuran_baju ?? '-' }}</td>
                            @break
                        @case('nomor_hp')
                            <td class="center">{{ $student->nomor_hp }}</td>
                            @break
                        @case('email')
                            <td>{{ $student->email }}</td>
                            @break
                        @case('alamat_lengkap')
                            <td style="font-size: 8px;">{{ $student->alamat_lengkap }}</td>
                            @break
                        @case('nama_ayah')
                            <td>{{ $student->nama_ayah }}</td>
                            @break
                        @case('nama_ibu')
                            <td>{{ $student->nama_ibu }}</td>
                            @break
                        @case('status')
                            <td class="center status-badge status-{{ $student->status ?? 'pending' }}">
                                {{ $student->status === 'diterima' ? 'LULUS' : ($student->status === 'ditolak' ? 'TOLAK' : 'PROSES') }}
                            </td>
                            @break
                        @case('submitted_at')
                            <td class="center font-mono">{{ $student->submitted_at?->format('d-m-Y H:i') ?? '-' }}</td>
                            @break
                        @default
                            <td>
                                @php
                                    $val = $student->additional_fields[$f] ?? '';
                                    if (is_array($val)) {
                                        $val = implode(', ', $val);
                                    }
                                    echo $val;
                                @endphp
                            </td>
                    @endswitch
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($selectedFields) + 1 }}" class="center" style="padding: 20px; font-style: italic; color: #64748b;">
                    Belum ada data pendaftar yang sesuai dengan filter pencarian.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
