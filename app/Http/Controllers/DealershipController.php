<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Dealership;
use Illuminate\View\View;

final class DealershipController extends Controller
{
    public function show(Dealership $dealership): View
    {
        return view('dealership.show', [
            'dealership' => $dealership,
        ]);
    }
}
