<?php

namespace App\Http\Controllers;
use App\Models\Pengeluaranbakso;
use App\Models\penggilingan;
use Illuminate\Http\Request;
use PDF;

class LaporanbaksoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tanggalAwal = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
        $tanggalAkhir = date('Y-m-d');

        if ($request->has('tanggal_awal') && $request->tanggal_awal != "" && $request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
        }

        return view('laporanbakso.index', compact('tanggalAwal', 'tanggalAkhir'));
    }

    public function getData($awal, $akhir)
    {
        $no = 1;
        $data = array();
        $pendapatan = 0;
        $total_pendapatan = 0;

        while (strtotime($awal) <= strtotime($akhir)) {
            $tanggal = $awal;
            $awal = date('Y-m-d', strtotime("+1 day", strtotime($awal)));

            $total_penggilinganbakso = penggilingan::where('created_at', 'LIKE', "%$tanggal%")->sum('bayar');
            $total_pengeluaranbakso = pengeluaranbakso::where('created_at', 'LIKE', "%$tanggal%")->sum('nominal');

            $pendapatan = $total_penggilinganbakso- $total_pengeluaranbakso;
            $total_pendapatan += $pendapatan;

            $row = array();
            $row['DT_RowIndex'] = $no++;
            $row['tanggal'] = tanggal_indonesia($tanggal, false);
            $row['penggilingan'] ='Rp. '.  format_uang($total_penggilinganbakso);
            $row['pengeluaran'] ='Rp. '.  format_uang($total_pengeluaranbakso);
            $row['pendapatan'] = 'Rp. '. format_uang($pendapatan);

            $data[] = $row;
        }

        $data[] = [
            'DT_RowIndex' => '',
            'tanggal' => '',
            'penggilingan' => '',
            'pengeluaran' => 'Total Pendapatan',
            'pendapatan' => 'Rp. '. format_uang($total_pendapatan),
        ];

        return $data;
    }

    public function data($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);

        return datatables()
            ->of($data)
            ->make(true);
    }

    public function exportPDF($awal, $akhir)
    {
        $data = $this->getData($awal, $akhir);
        $pdf  = PDF::loadView('laporanbakso.pdf', compact('awal', 'akhir', 'data'));
        $pdf->setPaper('a4', 'potrait');
        
        return $pdf->stream('Laporan-pendapatan-'. date('Y-m-d-his') .'.pdf');
    }
}

    