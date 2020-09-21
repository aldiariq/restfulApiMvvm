<?php

namespace App\Http\Controllers\OperasiMahasiswa;
use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'nim' => 'required|string|unique:mahasiswas',
            'nama' => 'required|string',
            'fotomhs' => 'required|image|mimes:jpeg,jpg,png'
        ]);

        if(!$validator->fails()){
            $fotomhs = $request->fotomhs;
            $namafilefotomhs = $fotomhs->getClientOriginalName();
            $fotomhs->storeAs('public/fotomhs', $namafilefotomhs);

            $mahasiswa = new Mahasiswa([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'foto' => $namafilefotomhs
            ]);

            if($mahasiswa->save()){
                return response()->json([
                    'isSuccessfull' => true,
                    'message' => "Berhasil Menambah Data Mahasiswa"
                ]);
            }else {
                return response()->json([
                    'isSuccessfull' => false,
                    'message' => "Gagal Menambah Data Mahasiswa"
                ]);
            }

        }else {
            return response()->json([
                'isSuccessfull' => false,
                'message' => "Gagal Menambahkan Data Mahasiswa"
            ], 401);
        }
    }

    public function getall(){
        $datamahasiswa = Mahasiswa::all();

        return response()->json([
            'isSuccessfull' => true,
            'mahasiswa' => $datamahasiswa
        ]);
    }

    public function getsingle($id){
        $datamahasiswa = Mahasiswa::find($id);

        return response()->json([
            'isSuccessfull' => true,
            'mahasiswa' => $datamahasiswa
        ]);
    }
}
