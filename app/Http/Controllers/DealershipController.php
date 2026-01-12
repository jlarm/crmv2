<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Dealership;

final class DealershipController extends Controller
{
    public function show(Dealership $dealership)
    {
        return view('dealership.show', [
            'dealership' => $dealership,
        ]);
    }
}
