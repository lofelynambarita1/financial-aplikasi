<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class FinancialLivewire extends Component
{
    use WithPagination;

    public $auth;
    
    // Search and Filter
    public $search = '';
    public $filterType = 'all';
    public $filterCategory = '';
    public $startDate = '';
    public $endDate = '';
    public $sortBy = 'transaction_date';
    public $sortDirection = 'desc';
    
    // Add Record
    public $addType;
    public $addTitle;
    public $addDescription;
    public $addAmount;
    public $addCategory;
    public $addTransactionDate;
    
    // Edit Record
    public $editRecordId;
    public $editType;
    public $editTitle;
    public $editDescription;
    public $editAmount;
    public $editCategory;
    public $editTransactionDate;
    
    // Delete Record
    public $deleteRecordId;
    public $deleteRecordTitle;
    public $deleteConfirmTitle;

    protected $queryString = ['search', 'filterType', 'filterCategory', 'startDate', 'endDate'];

    public function mount()
    {
        $this->auth = Auth::user();
        $this->addTransactionDate = date('Y-m-d');
        
        // Set default date range (bulan ini)
        if (empty($this->startDate)) {
            $this->startDate = date('Y-m-01');
        }
        if (empty($this->endDate)) {
            $this->endDate = date('Y-m-d');
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterType()
    {
        $this->resetPage();
    }

    public function updatingFilterCategory()
    {
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function render()
    {
        $query = FinancialRecord::where('user_id', $this->auth->id);

        // Search
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('category', 'like', '%' . $this->search . '%');
            });
        }

        // Filter by type
        if ($this->filterType !== 'all') {
            $query->where('type', $this->filterType);
        }

        // Filter by category
        if (!empty($this->filterCategory)) {
            $query->where('category', $this->filterCategory);
        }

        // Filter by date range
        if (!empty($this->startDate) && !empty($this->endDate)) {
            $query->betweenDates($this->startDate, $this->endDate);
        }

        // Sort
        $query->orderBy($this->sortBy, $this->sortDirection);

        $records = $query->paginate(20);

        // Get categories for filter
        $categories = FinancialRecord::where('user_id', $this->auth->id)
            ->distinct()
            ->pluck('category')
            ->toArray();

        // Calculate totals
        $totalIncome = FinancialRecord::where('user_id', $this->auth->id)
            ->income()
            ->betweenDates($this->startDate, $this->endDate)
            ->sum('amount');

        $totalExpense = FinancialRecord::where('user_id', $this->auth->id)
            ->expense()
            ->betweenDates($this->startDate, $this->endDate)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        return view('livewire.financial-livewire', [
            'records' => $records,
            'categories' => $categories,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'balance' => $balance
        ]);
    }

    // Add Record
    public function addRecord()
    {
        $this->validate([
            'addType' => 'required|in:income,expense',
            'addTitle' => 'required|string|max:255',
            'addDescription' => 'nullable|string',
            'addAmount' => 'required|numeric|min:0',
            'addCategory' => 'required|string|max:255',
            'addTransactionDate' => 'required|date',
        ]);

        FinancialRecord::create([
            'user_id' => $this->auth->id,
            'type' => $this->addType,
            'title' => $this->addTitle,
            'description' => $this->addDescription,
            'amount' => $this->addAmount,
            'category' => $this->addCategory,
            'transaction_date' => $this->addTransactionDate,
        ]);

        $this->reset(['addType', 'addTitle', 'addDescription', 'addAmount', 'addCategory']);
        $this->addTransactionDate = date('Y-m-d');
        
        $this->dispatch('closeModal', id: 'addRecordModal');
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Catatan keuangan berhasil ditambahkan.'
        ]);
    }

    // Edit Record
    public function prepareEditRecord($id)
    {
        $record = FinancialRecord::where('id', $id)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data tidak ditemukan.'
            ]);
            return;
        }

        $this->editRecordId = $record->id;
        $this->editType = $record->type;
        $this->editTitle = $record->title;
        $this->editDescription = $record->description;
        $this->editAmount = $record->amount;
        $this->editCategory = $record->category;
        $this->editTransactionDate = $record->transaction_date->format('Y-m-d');

        $this->dispatch('showModal', id: 'editRecordModal');
    }

    public function editRecord()
    {
        $this->validate([
            'editType' => 'required|in:income,expense',
            'editTitle' => 'required|string|max:255',
            'editDescription' => 'nullable|string',
            'editAmount' => 'required|numeric|min:0',
            'editCategory' => 'required|string|max:255',
            'editTransactionDate' => 'required|date',
        ]);

        $record = FinancialRecord::where('id', $this->editRecordId)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data tidak ditemukan.'
            ]);
            return;
        }

        $record->update([
            'type' => $this->editType,
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'amount' => $this->editAmount,
            'category' => $this->editCategory,
            'transaction_date' => $this->editTransactionDate,
        ]);

        $this->reset(['editRecordId', 'editType', 'editTitle', 'editDescription', 'editAmount', 'editCategory', 'editTransactionDate']);
        
        $this->dispatch('closeModal', id: 'editRecordModal');
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Catatan keuangan berhasil diubah.'
        ]);
    }

    // Delete Record
    public function prepareDeleteRecord($id)
    {
        $record = FinancialRecord::where('id', $id)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$record) {
            $this->dispatch('showAlert', [
                'icon' => 'error',
                'title' => 'Gagal!',
                'text' => 'Data tidak ditemukan.'
            ]);
            return;
        }

        $this->deleteRecordId = $record->id;
        $this->deleteRecordTitle = $record->title;
        $this->dispatch('showModal', id: 'deleteRecordModal');
    }

    public function deleteRecord()
    {
        if ($this->deleteConfirmTitle !== $this->deleteRecordTitle) {
            $this->addError('deleteConfirmTitle', 'Judul konfirmasi tidak sesuai.');
            return;
        }

        $record = FinancialRecord::where('id', $this->deleteRecordId)
            ->where('user_id', $this->auth->id)
            ->first();

        if ($record) {
            // Delete receipt if exists
            if ($record->receipt && \Storage::disk('public')->exists($record->receipt)) {
                \Storage::disk('public')->delete($record->receipt);
            }
            
            $record->delete();
        }

        $this->reset(['deleteRecordId', 'deleteRecordTitle', 'deleteConfirmTitle']);
        
        $this->dispatch('closeModal', id: 'deleteRecordModal');
        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Catatan keuangan berhasil dihapus.'
        ]);
    }

    public function resetFilters()
    {
        $this->reset(['search', 'filterType', 'filterCategory', 'startDate', 'endDate']);
        $this->startDate = date('Y-m-01');
        $this->endDate = date('Y-m-d');
        $this->resetPage();
    }
}