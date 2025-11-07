<?php

namespace App\Livewire;

use App\Models\FinancialRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class FinancialDetailLivewire extends Component
{
    use WithFileUploads;

    public $record;
    public $auth;

    // Upload Receipt
    public $uploadReceiptFile;

    public function mount($record_id)
    {
        $this->auth = Auth::user();

        $targetRecord = FinancialRecord::where('id', $record_id)
            ->where('user_id', $this->auth->id)
            ->first();

        if (!$targetRecord) {
            session()->flash('error', 'Data tidak ditemukan.');
            return redirect()->route('app.financial.index');
        }

        $this->record = $targetRecord;
    }

    public function render()
    {
        return view('livewire.financial-detail-livewire');
    }

    // Upload Receipt
    public function uploadReceipt()
    {
        $this->validate([
            'uploadReceiptFile' => 'required|image|max:2048',  // 2MB Max
        ]);

        if ($this->uploadReceiptFile) {
            // Delete old receipt if exists
            if ($this->record->receipt && Storage::disk('public')->exists($this->record->receipt)) {
                Storage::disk('public')->delete($this->record->receipt);
            }

            $userId = $this->auth->id;
            $dateNumber = now()->format('YmdHis');
            $extension = $this->uploadReceiptFile->getClientOriginalExtension();
            $filename = $userId . '_' . $dateNumber . '.' . $extension;
            $path = $this->uploadReceiptFile->storeAs('receipts', $filename, 'public');
            
            $this->record->receipt = $path;
            $this->record->save();

            $this->dispatch('closeModal', id: 'uploadReceiptModal');
            $this->dispatch('showAlert', [
                'icon' => 'success',
                'title' => 'Berhasil!',
                'text' => 'Bukti transaksi berhasil diunggah.'
            ]);
        }

        $this->reset(['uploadReceiptFile']);
    }

    // Delete Receipt
    public function deleteReceipt()
    {
        if ($this->record->receipt && Storage::disk('public')->exists($this->record->receipt)) {
            Storage::disk('public')->delete($this->record->receipt);
        }

        $this->record->receipt = null;
        $this->record->save();

        $this->dispatch('showAlert', [
            'icon' => 'success',
            'title' => 'Berhasil!',
            'text' => 'Bukti transaksi berhasil dihapus.'
        ]);
    }
}