<?php

namespace App\Livewire;

use App\Models\RateQoute;
use Livewire\Component;
use Livewire\WithPagination;

class RateQuotePagination extends Component
{
    use WithPagination;

    public $search = '';

    protected $updatesQueryString = ['search'];
    protected $listeners = ['searchTriggered' => 'performSearch'];

    public function mount()
    {
        $this->search = ''; // Initialize search
    }

    public function performSearch()
    {
        $this->resetPage(); // Reset to the first page when searching
    }

    public function render()
    {
        $data = RateQoute::query()
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('app', 'like', '%' . $this->search . '%')
            ->orWhere('address', 'like', '%' . $this->search . '%')
            ->orWhere('city', 'like', '%' . $this->search . '%')
            ->orWhere('province', 'like', '%' . $this->search . '%')
            ->orWhere('country', 'like', '%' . $this->search . '%')
            ->paginate(6);

        return view('livewire.rate-quote-pagination', ['data' => $data]);
    }
}
