@extends('layouts.app')

@section('content')
@php
    $ppdbFormOld = [
        'nama_lengkap' => old('nama_lengkap', ''),
        'nisn' => old('nisn', ''),
        'nomor_hp' => old('nomor_hp', ''),
        'email' => old('email', ''),
        'jenis_kelamin' => old('jenis_kelamin', ''),
        'tanggal_lahir' => old('tanggal_lahir', ''),
        'tempat_lahir' => old('tempat_lahir', ''),
        'nama_ayah' => old('nama_ayah', ''),
        'nama_ibu' => old('nama_ibu', ''),
        'alamat_lengkap' => old('alamat_lengkap', ''),
        'sekolah_asal' => old('sekolah_asal', ''),
        'ukuran_baju' => old('ukuran_baju', ''),
    ];
    foreach ($formFields as $field) {
        $ppdbFormOld[$field['id']] = old($field['id'], '');
    }

    $ppdbFieldSteps = [
        'nama_lengkap' => 1,
        'nisn' => 1,
        'jenis_kelamin' => 1,
        'tempat_lahir' => 1,
        'tanggal_lahir' => 1,
        'ukuran_baju' => 1,
        'alamat_lengkap' => 1,
        'foto_siswa' => 1,
        'nomor_hp' => 2,
        'email' => 2,
        'sekolah_asal' => 2,
        'nama_ayah' => 2,
        'nama_ibu' => 2,
    ];
    foreach ($requirements as $req) {
        if ($req['id'] !== 'foto') {
            $ppdbFieldSteps[$req['id']] = 3;
        }
    }
    foreach ($formFields as $field) {
        $ppdbFieldSteps[$field['id']] = 3;
    }

    $ppdbErrorStep = 1;
    $ppdbFirstErrorField = null;
    if ($errors->any()) {
        foreach ($errors->keys() as $key) {
            if (isset($ppdbFieldSteps[$key])) {
                $ppdbErrorStep = $ppdbFieldSteps[$key];
                $ppdbFirstErrorField = $key;
                break;
            }
        }
    }
@endphp

    @include('front.ppdb.partials.form.styles')
    @include('front.ppdb.partials.form.wizard-config')

    <section class="max-w-6xl mx-auto px-4 py-8 sm:py-12" x-data="ppdbFormWizard">
        @include('front.ppdb.partials.form.header')
        @include('front.ppdb.partials.form.errors')

        <div class="bg-white rounded-none p-6 sm:p-10 border border-gray-300 shadow-sm relative">
            @include('front.ppdb.partials.form.stepper')

            <form method="POST" action="{{ route('frontend.ppdb.store') }}" enctype="multipart/form-data">
                @csrf
                @include('front.ppdb.partials.form.steps.profile')
                @include('front.ppdb.partials.form.steps.contact')
                @include('front.ppdb.partials.form.steps.documents')
                @include('front.ppdb.partials.form.navigation')
            </form>
        </div>
    </section>

    @include('front.ppdb.partials.form.scripts')
@endsection
