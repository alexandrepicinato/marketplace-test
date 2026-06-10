<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('images')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],

            'accepts_affiliation' => ['nullable', 'boolean'],
            'commission_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
                Rule::requiredIf($request->boolean('accepts_affiliation')),
            ],

            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $requiresApproval = AppSetting::bool('require_product_approval', false);

        $dados['accepts_affiliation'] = $request->boolean('accepts_affiliation');

        if ($requiresApproval) {
            $dados['active'] = false;
            $dados['approval_status'] = 'pending';
            $dados['approved_at'] = null;
            $dados['approved_by'] = null;
        } else {
            $dados['active'] = $request->boolean('active');
            $dados['approval_status'] = 'approved';
            $dados['approved_at'] = now();
            $dados['approved_by'] = auth()->id();
        }

        if (!$dados['accepts_affiliation']) {
            $dados['commission_percentage'] = null;
        }

        unset($dados['images']);

        $dados['slug'] = $this->generateUniqueSlug($dados['name']);

        $product = auth()->user()->products()->create($dados);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_main' => $index === 0,
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto cadastrado com sucesso.');
    }

    public function edit(Product $product)
    {
        $this->checkProductOwner($product);

        $product->load('images');

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->checkProductOwner($product);

        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'active' => ['nullable', 'boolean'],

            'accepts_affiliation' => ['nullable', 'boolean'],
            'commission_percentage' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
                Rule::requiredIf($request->boolean('accepts_affiliation')),
            ],

            'images' => ['nullable', 'array', 'max:5'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $dados['active'] = $request->boolean('active');
        $dados['accepts_affiliation'] = $request->boolean('accepts_affiliation');

        if (!$dados['accepts_affiliation']) {
            $dados['commission_percentage'] = null;
        }

        unset($dados['images']);

        if ($product->name !== $dados['name']) {
            $dados['slug'] = $this->generateUniqueSlug($dados['name'], $product->id);
        }

        $product->update($dados);

        if ($request->hasFile('images')) {
            $hasMainImage = $product->images()->where('is_main', true)->exists();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_main' => !$hasMainImage && $index === 0,
                ]);
            }
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product)
{
    $this->checkProductOwner($product);

    $product->update([
        'active' => false,
        'deleted_at' => now(),
    ]);

    return redirect()
        ->route('products.index')
        ->with('success', 'Produto desativado com sucesso. O registro foi mantido no banco.');
}

    public function destroyImage(ProductImage $image)
    {
        $product = $image->product;

        $this->checkProductOwner($product);

        Storage::disk('public')->delete($image->path);

        $image->delete();

        return back()->with('success', 'Imagem removida com sucesso.');
    }

    private function checkProductOwner(Product $product): void
    {
        if ($product->user_id !== auth()->id()) {
            abort(403, 'Você não tem permissão para acessar este produto.');
        }
    }

    private function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}