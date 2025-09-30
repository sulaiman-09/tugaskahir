<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SudirmanParkController extends Controller
{
    public function index()
    {
        // Nanti bisa diisi data dari database, sementara kita tampilkan view kosong
        return view('sudirman_park.index');
    }
}