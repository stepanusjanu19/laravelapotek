<?php

namespace App\Http\Controllers;

use App\Dokter;
use App\NoAntrian;
use App\Pasien;
use Barryvdh\DomPDF\PDF;
use Excel;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResepsionistController extends Controller
{
    public function __construct() {
    	  $this->middleware('resepsionist');
    }

    public function index() {
            $pasien = Pasien::with('no_antrian')->whereDate('created_at', '=', date('Y-m-d'))->where('status', '=', 'antri')->get();
            $total = Pasien::where('status', 'selesai')->get()->toArray();
            $bulan = Pasien::where('status', 'selesai')->whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->get()->toArray();
            $dokter = Dokter::with('spesialis')->get()->toArray();
            $id = Pasien::select('id')->get()->last();
            if ($id == null) {
                $id = 1;
            }
            $id  = substr($id['id'], 4);
            $id = (int) $id;
            $id += 1;
            $id  = "PS" . str_pad($id, 4, "0", STR_PAD_LEFT);
    	return view('resepsionist.index', [
            'pasien' => $pasien,
            'id' => $id,
            'total' => $total,
            'bulan' => $bulan,
            'dokter' => $dokter
        ]);
    }

    public function getPasien() {
        $HariIni = Pasien::whereDate('created_at', '=', date('Y-m-d'))->where('status', '=', 'antri')->get();
        $bulan = Pasien::whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->get()->toArray();
        $pasien = Pasien::orderBy('created_at', 'desc')->groupBy('nama')->get()->toArray();
        $dokter = Dokter::with('spesialis')->get()->toArray();
    	return view('resepsionist.pasien.index', ['pasien'=> $pasien, 'bulan' => $bulan, 'HariIni' => $HariIni, 'dokter' => $dokter]);
    }

    public function postPendaftaranPasien(Request $request) {
            try {
                DB::beginTransaction();

                $data = Pasien::create($request->all());

                $pasien_id = $this->getLastNoAntrian();

                $pasien = Pasien::whereDate('created_at', '=', date('Y-m-d'))->where('status', '=', 'antri')->get();
                $total = Pasien::where('status', 'selesai')->get()->toArray();
                $bulan = Pasien::where('status', 'selesai')->whereMonth('created_at', '=', date('m'))->whereYear('created_at', '=', date('Y'))->get()->toArray();

                // create no antrian
                $id = NoAntrian::select('id')->get()->last();
                $id=$id['id'];
                if ($id == null) {
                    $id = 1;
                } else {
                    $id = (int) $id;
                    $id += 1;
                }
                $id  = str_pad($id, 3, "0", STR_PAD_LEFT);
                $antrian = NoAntrian::create(["no" => $id, 'pasien_id' => $data['id']]);

                DB::commit();
                return response()->json([
                    "success" => [
                        "data" => $data,
                        "id" => $pasien_id,
                        "id_antrian" => $antrian->id,
                        "no_antrian" => $antrian->no,
                        "pasien_hari_ini" => count($pasien),
                        "total_pasien" => count($total),
                        "total_per_bulan" => count($bulan)
                    ],
                    "errors" => null
                ], 200);
            } catch (\Exception $e) {
                DB::rollback();
                $errors = $e->errorInfo[2];
                return response()->json([
                    "success" => null,
                    "errors" => $errors
                ], 400);
            }
    }

    public function postPasienTerdaftar(Request $request) {
        if($request->ajax()){
            $pasien = Pasien::find($request->id);
            $id = Pasien::select('id')->get()->last();
            if ($id == null) {
                $id = 1;
            }
            $id  = substr($id['id'], 4);
            $id = (int) $id;
            $id += 1;
            $id  = "PS" . str_pad($id, 4, "0", STR_PAD_LEFT);
            $create_pasien = Pasien::create([
                'id' => $id,
                'nama' => $pasien->nama,
                'jenis_kelamin' => $pasien->jenis_kelamin,
                'alamat' => $pasien->alamat,
                'tgl_lahir' => $pasien->tgl_lahir,
                'telp' => $pasien->telp,
                'pekerjaan' => $pasien->pekerjaan,
                'status' => 'antri',
                'layanan_dokter' => $request->dokter_id
            ]);

            // create no antrian
            $id = NoAntrian::select('id')->get()->last();
            $id= $id['id'];
            if ($id == null) {
                $id = 1;
            } else {
                $id = (int) $id;
                $id += 1;
            }
            $id  = str_pad($id, 3, "0", STR_PAD_LEFT);
            $antrian = NoAntrian::create(["no" => $id, 'pasien_id' => $create_pasien['id']]);
            return response()->json($create_pasien);
        }
    }

    public function getHapusPasien(Request $request) {
        if ($request->ajax()) {
            $data = Pasien::find($request->id)->delete();
            $deleteNoAntrian = NoAntrian::where('pasien_id', $request->id)->delete();
            return response()->json($data);
        }
    }

    public function postUpdatePasien(Request $request) {
        if ($request->ajax()) {
            $data = Pasien::find($request->id)->update($request->all());
            return response()->json($data);
        }
    }

    public function exportExcelPasien(Request $request, $type) {
         Excel::create('Data Pasien ' .  $request->bulan .'-' .$request->tahun, function ($excel) use ($request){
                $excel->sheet('Data Pasien ' .  $request->bulan .'-' .$request->tahun, function ($sheet) use ($request){
                    $bulan = $request->bulan;
                    $tahun = $request->tahun;
                    $arr = array();
                    $barang = Pasien::whereMonth('created_at', $bulan)->whereYear('created_at', $tahun)->get()->toArray();
                    foreach ($barang as $data) {
                        $data_arr = array(
                            $data['id'],
                            $data['nama'],
                            $data['jenis_kelamin'],
                            $data['tgl_lahir'],
                            $data['pekerjaan'],
                            $data['telp'],
                            $data['alamat'],
                        );
                        array_push($arr, $data_arr);
                    }
                    $sheet->fromArray($arr, null, 'A1', false, false)->prependRow(array(
                        'ID',
                        'Nama Pasien',
                        'Jenis Kelamin',
                        'Tgl. Lahir',
                        'Pekerjaan',
                        'No. Telp',
                        'Alamat',
                    ));
                });
            })->download($type);
    }

     public function exportPDFPasien(Request $request) {
         $bulan = $request->bulan;
         $tahun = $request->tahun;
         $pasien = Pasien::whereMonth('created_at', $bulan)
                            ->whereYear('created_at', $tahun)
                            ->get()->toArray();
        // $pdf = PDF::render();
        return view('resepsionist.pasien.pdf', ['bulan' => $bulan, 'tahun' => $tahun, 'pasien' => $pasien]);
    }

    public function no_antrian($pasien_id) {
        $no_antrian = NoAntrian::where('pasien_id', $pasien_id)->first()->no;
        return view('resepsionist.no_antrian.index', ['data' => $no_antrian]);
        // $no = NoAntrian::select('id')->get()->last();
        // if ($no == null) {
        //     $no = 1;
        // }
        // $no  = substr($no['no'], 3);
        // $no = (int) $no;
        // $no += 1;
        // $no  = str_pad($no, 3, "0", STR_PAD_LEFT);
        // $no_antrian = NoAntrian::create([
        //     'no' => $no
        // ]);

        // $pdf = PDF::loadView('no_antrian.pdf', [
        //     'no' => $no
        // ])->setPaper($size)->setOptions(['dpi' => 72,'defaultFont' => 'sans-serif']);

        // return $pdf->stream('No.' . $no . '.pdf');
    }

    public function getLastNoAntrian() {
        $id = Pasien::select('id')->get()->last();
            if ($id == null) {
                $id = 1;
            }
            $id  = substr($id['id'], 4);
            $id = (int) $id;
            $id += 1;
            $id  = "PS" . str_pad($id, 4, "0", STR_PAD_LEFT);

        return $id;
    }

    public function resetNoAntrian() {
        try {
            NoAntrian::query()->truncate();
            $data = ["success" => "berhasil mereset no antrian.", "error" => null];
        } catch (Exception $e) {
            $data = ["success" => null, "errors" => "gagal mereset no antrian."];
        }
        $data["last_id"] = $this->getLastNoAntrian();
        return response()->json($data);
    }
}
