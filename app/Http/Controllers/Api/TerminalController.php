<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Terminal;


class TerminalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $terminals = Terminal::paginate();

        if ($terminals->count() > 0) {
            return response()->json(['message' => 'Data Terminal berhasil di GET', 'data' => $terminals]);
        } else {
            return response()->json(['message' => 'Data Terminal masih kosong']);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'city'    => 'required',
            'province'  => 'required',
        ]);

        $terminal = Terminal::create($request->all());

        if ($terminal) {
            return response()->json(['message' => 'Terminal berhasil diinput', 'data' => $terminal]);
        } else {
            return response()->json(['message' => 'Terminal gagal diinput']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Terminal $terminal)
    {
        if ($terminal) {
            return response()->json(['message' => 'Data Terminal berhasil di GET', 'data' => $terminal]);
        } else {
            return response()->json(['message' => 'Data Terminal tidak ditemukan']);
        }
    }

    public function update(Request $request, Terminal $terminal)
    {
        $request->validate([
            'name'      => 'required',
            'city'    => 'required',
            'province'  => 'required',
        ]);

        $terminal->update($request->all());

        if ($terminal) {
            return response()->json(['message' => 'Terminal berhasil diupdate', 'data' => $terminal]);
        } else {
            return response()->json(['message' => 'Terminal gagal diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Terminal $terminal)
    {
        $terminal->delete();

        if ($terminal) {
            return response()->json(['message' => 'Terminal berhasil dihapus', 'data' => $terminal]);
        } else {
            return response()->json(['message' => 'Terminal gagal dihapus']);
        }
    }
}
