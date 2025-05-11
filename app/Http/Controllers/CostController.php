<?php

namespace App\Http\Controllers;

use App\Actions\Cost\DeleteCostAction;
use App\Actions\Cost\StoreCostAction;
use App\Actions\Cost\UpdateCostAction;
use App\Http\Requests\Cost\StoreCostRequest;
use App\Http\Requests\Cost\UpdateCostRequest;
use App\Models\Cost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CostController extends Controller
{
    public function index(Request $request): View
    {
        $userId = $request->user()->id;
        $categoryId = $request->query('category_id');
        
        // Get categories for the filter
        $categories = $request->user()->categories;
        
        // Build the query
        $costsQuery = $request->user()->costs();
        
        // Apply category filter if provided
        if ($categoryId) {
            $costsQuery->where('category_id', $categoryId);
        }
        
        // Paginate the results
        $costs = $costsQuery->paginate(10)->withQueryString();
        
        // Get the current year's monthly expense data (filtered by category if needed)
        $monthlyExpenses = $this->getMonthlyExpenses($userId, $categoryId);
        
        // Get expense statistics (filtered by category if needed)
        $stats = [
            'totalExpenses' => $this->getTotalExpenses($userId, $categoryId),
            'yearlyExpenses' => $this->getCurrentYearExpenses($userId, $categoryId),
            'monthlyExpenses' => $this->getCurrentMonthExpenses($userId, $categoryId)
        ];
        
        return view('costs.index', compact('costs', 'monthlyExpenses', 'stats', 'categories', 'categoryId'));
    }
    
    /**
     * Get monthly expenses for the current year
     */
    private function getMonthlyExpenses(int $userId, ?int $categoryId = null): array
    {
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($currentYear, 1, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($currentYear, 12, 31)->endOfMonth();
        
        $query = Cost::select(DB::raw('MONTH(paid_at) as month'), DB::raw('SUM(amount) as total'))
            ->where('user_id', $userId)
            ->whereBetween('paid_at', [$startDate, $endDate]);
            
        // Apply category filter if provided
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        $monthlyData = $query->groupBy(DB::raw('MONTH(paid_at)'))
            ->orderBy('month')
            ->get();
        
        // Initialize data array with all months set to 0
        $data = array_fill(1, 12, 0);
        
        // Fill in the actual data
        foreach ($monthlyData as $item) {
            $data[$item->month] = (int) $item->total;
        }
        
        // Prepare month names (localized)
        $monthNames = [
            1 => __('January'),
            2 => __('February'),
            3 => __('March'),
            4 => __('April'),
            5 => __('May'),
            6 => __('June'),
            7 => __('July'),
            8 => __('August'),
            9 => __('September'),
            10 => __('October'),
            11 => __('November'),
            12 => __('December'),
        ];
        
        return [
            'labels' => array_values($monthNames),
            'data' => array_values($data),
            'year' => $currentYear
        ];
    }
    
    /**
     * Get total expenses for a user
     */
    private function getTotalExpenses(int $userId, ?int $categoryId = null): int
    {
        $query = Cost::where('user_id', $userId);
        
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        return (int) $query->sum('amount');
    }
    
    /**
     * Get current year expenses for a user
     */
    private function getCurrentYearExpenses(int $userId, ?int $categoryId = null): int
    {
        $currentYear = Carbon::now()->year;
        $startDate = Carbon::createFromDate($currentYear, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($currentYear, 12, 31)->endOfDay();
        
        $query = Cost::where('user_id', $userId)
            ->whereBetween('paid_at', [$startDate, $endDate]);
            
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        return (int) $query->sum('amount');
    }
    
    /**
     * Get current month expenses for a user
     */
    private function getCurrentMonthExpenses(int $userId, ?int $categoryId = null): int
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth()->startOfDay();
        $endOfMonth = $now->copy()->endOfMonth()->endOfDay();
        
        $query = Cost::where('user_id', $userId)
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth]);
            
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        
        return (int) $query->sum('amount');
    }


    public function create(Request $request): View
    {
        $categories = $request->user()->categories;

        return view('costs.create', compact('categories'));
    }


    public function store(StoreCostRequest $request, StoreCostAction $action): RedirectResponse
    {
        $user = $request->user();

        $action->handle($user, $request->validated());

        return redirect()->route('costs.index');
    }


    public function edit(Cost $cost): View
    {
        Gate::authorize('update', $cost);

        $categories = $cost->user->categories;

        return view('costs.edit', compact('cost', 'categories'));
    }


    public function update(UpdateCostRequest $request, Cost $cost, UpdateCostAction $action): RedirectResponse
    {
        Gate::authorize('update', $cost);

        $action->handle($cost, $request->validated());

        return redirect()->route('costs.index');
    }


    public function destroy(Cost $cost, DeleteCostAction $action): RedirectResponse
    {
        Gate::authorize('delete', $cost);

        $action->handle($cost);

        return redirect()->route('costs.index');
    }
}
