<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuCatalogController extends Controller
{
    /**
     * Menampilkan kerangka awal katalog menu berdasarkan parameter meja.
     */
    public function index(Request $request): View
    {
        $tableParam = $request->query('table');
        $tokenParam = $request->query('token');

        $table = Table::query()
            ->when($tokenParam, fn ($q) => $q->where('qr_token', $tokenParam))
            ->when(! $tokenParam && $tableParam, fn ($q) => $q->where('table_number', str_pad((string) $tableParam, 2, '0', STR_PAD_LEFT)))
            ->first();

        if (! $table) {
            abort(404, 'Nomor meja tidak ditemukan atau QR Code tidak valid.');
        }

        $categories = Category::orderBy('id')->get();
        $menus = Menu::with('category')->orderBy('category_id')->orderBy('id')->get();

        return view('customer.catalog', compact('table', 'categories', 'menus'));
    }
}
