<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contents = Storage::get('templates.json');


        return response()->json(json_decode($contents));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if ($id === 'options') {

            $contents = Storage::get('options.json');

        }

        if ($id === 'templates') {

            $contents = Storage::get('templates.json');

        }

        if ($id === 'documentation') {

            $contents = Storage::get('documentation.json');

        }

        return response()->json(json_decode($contents));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $contents = $request->data;

        if ($id === 'options') {

            Storage::put('options.json', json_encode($contents));

        }

        if ($id === 'templates') {

            Storage::put('templates.json', json_encode($contents));

        }

        if ($id === 'documentation') {

            Storage::put('documentation.json', json_encode($contents));

        }


        return response('', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
