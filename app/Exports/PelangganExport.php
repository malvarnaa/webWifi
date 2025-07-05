<?php

namespace App\Exports;

use App\Models\Register;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PelangganExport implements FromCollection, WithHeadings
{
    protected $start, $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection()
    {
        return Register::where('status', 'diterima')
            ->whereBetween('tanggal_diterima', [$this->start, $this->end])
            ->get([
                'id', 'nama_cust', 'email', 'nomor_hp', 'nik',
                'alamat_lengkap', 'status_kepelangganan', 'tanggal_diterima', 'jatuh_tempo'
            ]);
    }

    public function headings(): array
    {
        return [
            'ID', 'Nama', 'Email', 'Nomor HP', 'NIK',
            'Alamat', 'Status Kepelangganan', 'Tanggal Diterima', 'Jatuh Tempo'
        ];
    }
}
