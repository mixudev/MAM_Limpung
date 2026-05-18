<?php

namespace App\Http\Controllers\Dashboard\Ppdb;

use App\Http\Controllers\Controller;
use App\Models\PpdbSetting;
use App\Models\PpdbSiswa;
use Illuminate\View\View;

class AdminPpdbPrintController extends Controller
{
    /**
     * Print candidate details in professional school-standard A4 layout.
     */
    public function print(PpdbSiswa $ppdbSiswa): View
    {
        $customFields = PpdbSetting::getValue('form_fields', []);

        return view('dashboard.admin.ppdb.print', [
            'student' => $ppdbSiswa,
            'customFields' => $customFields,
        ]);
    }
}
