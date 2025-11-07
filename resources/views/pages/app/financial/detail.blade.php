{{-- resources/views/pages/app/financial/detail.blade.php --}}
@extends('layouts.app')

@section('content')
    @livewire('financial-detail-livewire', ['record_id' => $record_id])
@endsection