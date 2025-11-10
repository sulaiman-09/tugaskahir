<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class BannersExport implements FromCollection, WithHeadings
{
    protected $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    public function collection()
    {
        return $this->items->map(function ($i) {
            return [
                'ID' => $i->id,
                'Name' => $i->name,
                'Web Image' => $i->web_image,
                'Mobile Image' => $i->mobile_image,
                'Created At' => $i->created_at,
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Name', 'Web Image', 'Mobile Image', 'Created At'];
    }
}
