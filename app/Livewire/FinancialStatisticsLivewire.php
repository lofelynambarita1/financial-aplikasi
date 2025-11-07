<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinancialStatisticsLivewire extends Component
{
    public $auth;
    public $selectedYear;
    public $selectedMonth;
    
    public function mount()
    {
        $this->auth = Auth::user();
        $this->selectedYear = date('Y');
        $this->selectedMonth = date('m');
    }

    public function render()
    {
        // Monthly trend data (12 months)
        $monthlyData = FinancialRecord::select(
        DB::raw('EXTRACT(MONTH FROM transaction_date) AS month'),
        DB::raw('SUM(CASE WHEN type = \'income\' THEN amount ELSE 0 END) AS total_income'),
        DB::raw('SUM(CASE WHEN type = \'expense\' THEN amount ELSE 0 END) AS total_expense')
    )
    ->where('user_id', $this->auth->id)
    ->whereRaw('EXTRACT(YEAR FROM transaction_date) = ?', [$this->selectedYear])
    ->groupBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
    ->orderBy(DB::raw('EXTRACT(MONTH FROM transaction_date)'))
    ->get();


        // Category breakdown for selected month
        $categoryData = FinancialRecord::where('user_id', $this->auth->id)
            ->whereRaw('EXTRACT(YEAR FROM transaction_date) = ?', [$this->selectedYear])
            ->whereRaw('EXTRACT(MONTH FROM transaction_date) = ?', [$this->selectedMonth])
            ->select('category', 'type', DB::raw('SUM(amount) as total'))
            ->groupBy('category', 'type')
            ->get();

        // Total summary
        $totalIncome = FinancialRecord::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->income()
            ->sum('amount');

        $totalExpense = FinancialRecord::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->expense()
            ->sum('amount');

        // Top categories
        $topIncomeCategories = FinancialRecord::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->income()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $topExpenseCategories = FinancialRecord::where('user_id', $this->auth->id)
            ->whereYear('transaction_date', $this->selectedYear)
            ->expense()
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('livewire.financial-statistics-livewire', [
            'monthlyData' => $monthlyData,
            'categoryData' => $categoryData,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'topIncomeCategories' => $topIncomeCategories,
            'topExpenseCategories' => $topExpenseCategories
        ]);
    }

    public function updatedSelectedYear()
    {
        // Refresh data when year changes
    }

    public function updatedSelectedMonth()
    {
        // Refresh data when month changes
    }
}