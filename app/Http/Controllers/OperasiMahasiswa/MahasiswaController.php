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
            'fotomhs' => 'required|image|mimes:jpeg,jpg,png',
            'namafotomhs' => 'required|string'
        ]);

        if(!$validator->fails()){
            $fotomhs = $request->fotomhs;
            $namafilefotomhs = $fotomhs->getClientOriginalName();
            $fotomhs->storeAs('public/fotomhs', $namafilefotomhs);

            $mahasiswa = new Mahasiswa([
                'nim' => $request->nim,
                'nama' => $request->nama,
                'foto' => $request->namafotomhs
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

    public function update($id, Request $request){
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|unique:mahasiswas',
            'nama' => 'required|string',
            'fotomhs' => 'required|image|mimes:jpeg,jpg,png',
            'namafotomhs' => 'required|string'
        ]);

        if (!$validator->fails()) {
            $fotomhs = $request->fotomhs;
            $namafilefotomhs = $fotomhs->getClientOriginalName();
            $fotomhs->storeAs('public/fotomhs', $namafilefotomhs);

            $datamahasiswa = Mahasiswa::find($id);
            $datamahasiswa->nim = $request->nim;
            $datamahasiswa->nama = $request->nama;
            $datamahasiswa->foto = $request->namafotomhs;

            if($datamahasiswa->save()){
                return response()->json([
                    'isSuccessfull' => true,
                    'message' => 'Berhasil Mengupdate Data Mahasiswa'
                ]);
            }else {
                return response()->json([
                    'isSuccessfull' => false,
                    'message' => 'Gagal Mengupdate Data Mahasiswa'
                ]);
            }
        }else {
            return response()->json([
                'isSuccessfull' => false,
                'message' => 'Gagal Mengupdate Data Mahasiswa'
            ]);
        }
    }

    public function delete($id){
        $datamahasiswa = Mahasiswa::find($id);

        if($datamahasiswa->delete()){
            return response()->json([
                'isSuccessfull' => true,
                'message' => 'Berhasil Menghapus Data Mahasiswa'
            ]);
        }else {
            return response()->json([
                'isSuccessfull' => false,
                'message' => 'Gagal Menghapus Data Mahasiswa'
            ]);
        }
    }
}
