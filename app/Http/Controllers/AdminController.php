<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\ProductAffiliate;
use App\Models\AppSetting;
use App\Models\OrderTrackingEvent;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private function ensureAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Acesso administrativo negado.');
        }
    }

    public function dashboard()
    {
        $this->ensureAdmin();

        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'pending_products' => Product::where('approval_status', 'pending')->count(),
            'active_products' => Product::where('active', true)->whereNull('deleted_at')->count(),
            'orders' => Order::whereNull('deleted_at')->count(),
            'affiliations' => ProductAffiliate::where('status', 'active')->count(),
            'revenue' => Order::whereNull('deleted_at')->sum('subtotal'),
        ];

        $latestOrders = Order::with(['buyer', 'seller', 'product', 'affiliate'])
            ->whereNull('deleted_at')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'latestOrders'));
    }

    public function users()
    {
        $this->ensureAdmin();

        $users = User::withCount(['products', 'ordersAsBuyer', 'ordersAsSeller'])
            ->latest()
            ->get();

        return view('admin.users', compact('users'));
    }

    public function products()
    {
        $this->ensureAdmin();

        $products = Product::with(['user', 'images', 'approvedBy'])
            ->latest()
            ->get();

        return view('admin.products', compact('products'));
    }

    public function updateProductApproval(Request $request, Product $product)
    {
        $this->ensureAdmin();

        $dados = $request->validate([
            'approval_status' => ['required', 'in:pending,approved,rejected'],
            'rejection_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($dados['approval_status'] === 'approved') {
            $product->update([
                'approval_status' => 'approved',
                'active' => true,
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'rejection_reason' => null,
            ]);
        }

        if ($dados['approval_status'] === 'pending') {
            $product->update([
                'approval_status' => 'pending',
                'active' => false,
                'approved_at' => null,
                'approved_by' => null,
                'rejection_reason' => null,
            ]);
        }

        if ($dados['approval_status'] === 'rejected') {
            $product->update([
                'approval_status' => 'rejected',
                'active' => false,
                'approved_at' => null,
                'approved_by' => null,
                'rejection_reason' => $dados['rejection_reason'] ?? 'Produto rejeitado pelo administrador.',
            ]);
        }

        return back()->with('success', 'Estado de aprovação atualizado.');
    }

    public function disableProduct(Product $product)
    {
        $this->ensureAdmin();

        $product->update([
            'active' => false,
            'deleted_at' => now(),
        ]);

        return back()->with('success', 'Produto desativado. O registro foi mantido no banco.');
    }

    public function restoreProduct(Product $product)
    {
        $this->ensureAdmin();

        $product->update([
            'deleted_at' => null,
            'active' => $product->approval_status === 'approved',
        ]);

        return back()->with('success', 'Produto restaurado.');
    }

    public function orders()
    {
        $this->ensureAdmin();

        $orders = Order::with([
            'buyer',
            'seller',
            'product',
            'affiliate',
            'paymentInfo',
            'shippingAddress',
        ])
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->ensureAdmin();

        $dados = $request->validate([
            'status' => ['required', 'in:pending,paid,processing,shipped,delivered,canceled,refunded'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $order->update([
            'status' => $dados['status'],
            'admin_notes' => $dados['admin_notes'] ?? $order->admin_notes,
        ]);

        return back()->with('success', 'Status do pedido atualizado.');
    }

    public function tracking(Order $order)
    {
        $this->ensureAdmin();

        $order->load(['buyer', 'product', 'trackingEvents.admin']);

        return view('admin.tracking', compact('order'));
    }

    public function updateTracking(Request $request, Order $order)
    {
        $this->ensureAdmin();

        $dados = $request->validate([
            'tracking_code' => ['nullable', 'string', 'max:255'],
            'tracking_carrier' => ['nullable', 'string', 'max:255'],
            'tracking_status' => ['required', 'string', 'max:255'],
            'tracking_url' => ['nullable', 'url', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'event_at' => ['nullable', 'date'],
        ]);

        $order->update([
            'tracking_code' => $dados['tracking_code'] ?? null,
            'tracking_carrier' => $dados['tracking_carrier'] ?? null,
            'tracking_status' => $dados['tracking_status'],
            'tracking_url' => $dados['tracking_url'] ?? null,
        ]);

        OrderTrackingEvent::create([
            'order_id' => $order->id,
            'admin_user_id' => auth()->id(),
            'status' => $dados['tracking_status'],
            'location' => $dados['location'] ?? null,
            'tracking_code' => $dados['tracking_code'] ?? null,
            'tracking_carrier' => $dados['tracking_carrier'] ?? null,
            'tracking_url' => $dados['tracking_url'] ?? null,
            'description' => $dados['description'] ?? null,
            'event_at' => $dados['event_at'] ?? now(),
        ]);

        return back()->with('success', 'Rastreio atualizado.');
    }

    public function settings()
    {
        $this->ensureAdmin();

        $settings = [
            'require_product_approval' => AppSetting::bool('require_product_approval'),
            'marketplace_enabled' => AppSetting::bool('marketplace_enabled', true),
            'checkout_enabled' => AppSetting::bool('checkout_enabled', true),
            'affiliation_enabled' => AppSetting::bool('affiliation_enabled', true),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $this->ensureAdmin();

        AppSetting::setValue('require_product_approval', $request->boolean('require_product_approval') ? '1' : '0', 'boolean');
        AppSetting::setValue('marketplace_enabled', $request->boolean('marketplace_enabled') ? '1' : '0', 'boolean');
        AppSetting::setValue('checkout_enabled', $request->boolean('checkout_enabled') ? '1' : '0', 'boolean');
        AppSetting::setValue('affiliation_enabled', $request->boolean('affiliation_enabled') ? '1' : '0', 'boolean');

        return back()->with('success', 'Configurações atualizadas.');
    }
    
    public function updateUserStatus(Request $request, User $user)
    {
        $this->ensureAdmin();

        if ($user->id === auth()->id()) {
            return back()->withErrors([
                'user' => 'Você não pode alterar o status da própria conta administrativa.',
            ]);
        }

        $dados = $request->validate([
            'account_status' => ['required', 'in:active,inactive,suspended'],
            'suspended_until' => ['nullable', 'date'],
            'suspension_reason' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($dados['account_status'] === 'active') {
            $user->update([
                'account_status' => 'active',
                'suspended_until' => null,
                'suspension_reason' => null,
                'disabled_at' => null,
            ]);
        }

        if ($dados['account_status'] === 'inactive') {
            $user->update([
                'account_status' => 'inactive',
                'suspended_until' => null,
                'suspension_reason' => $dados['suspension_reason'] ?? 'Conta desativada pelo administrador.',
                'disabled_at' => now(),
            ]);
        }

        if ($dados['account_status'] === 'suspended') {
            $user->update([
                'account_status' => 'suspended',
                'suspended_until' => $dados['suspended_until'] ?? null,
                'suspension_reason' => $dados['suspension_reason'] ?? 'Conta suspensa pelo administrador.',
                'disabled_at' => null,
            ]);
        }

        return back()->with('success', 'Status do usuário atualizado com sucesso.');
    }

    public function affiliateEarnings()
    {
        $this->ensureAdmin();

        $orders = Order::with([
            'product',
            'buyer',
            'seller',
            'affiliate',
            'commissionValidator',
        ])
            ->whereNotNull('affiliate_user_id')
            ->where('commission_amount', '>', 0)
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        $summary = [
            'pending' => $orders->where('commission_status', 'pending')->sum('commission_amount'),
            'approved' => $orders->where('commission_status', 'approved')->sum('commission_amount'),
            'rejected' => $orders->where('commission_status', 'rejected')->sum('commission_amount'),
            'paid' => $orders->where('commission_status', 'paid')->sum('commission_amount'),
            'total' => $orders->sum('commission_amount'),
        ];

        return view('admin.affiliate-earnings', compact('orders', 'summary'));
    }

    public function updateAffiliateCommission(Request $request, Order $order)
    {
        $this->ensureAdmin();

        if (!$order->affiliate_user_id || $order->commission_amount <= 0) {
            return back()->withErrors([
                'commission' => 'Este pedido não possui comissão de afiliado.',
            ]);
        }

        $dados = $request->validate([
            'commission_status' => ['required', 'in:pending,approved,rejected,paid'],
            'commission_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $update = [
            'commission_status' => $dados['commission_status'],
            'commission_notes' => $dados['commission_notes'] ?? null,
            'commission_validated_by' => auth()->id(),
            'commission_validated_at' => now(),
        ];

        if ($dados['commission_status'] === 'paid') {
            $update['commission_paid_at'] = now();
        } else {
            $update['commission_paid_at'] = null;
        }

        $order->update($update);

        return back()->with('success', 'Comissão atualizada com sucesso.');
    }

}