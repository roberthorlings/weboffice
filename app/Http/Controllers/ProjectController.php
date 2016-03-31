<?php

namespace Weboffice\Http\Controllers;

use Weboffice\Http\Controllers\Controller;

use Flash;
use Illuminate\Http\Request;
use Weboffice\Models\Project;

class ProjectController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $project = Project::paginate(15);

        return view('project.index', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Models\Relation::lists("bedrijfsnaam", "id");
		$lists["post_id"] = \Weboffice\Models\Post::all()->lists("description", "id");
    
        return view('project.create', compact('lists'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        
        Project::create($request->all());

        Flash::message( 'Project added!');

        return redirect('project');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function show($id)
    {
        $project = Project::findOrFail($id);

        return view('project.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $project = Project::findOrFail($id);
    	$lists = [];
    	$lists["relatie_id"] = \Weboffice\Models\Relation::lists("bedrijfsnaam", "id");
		$lists["post_id"] = \Weboffice\Models\Post::all()->lists("description", "id");

        return view('project.edit', compact('lists', 'project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function update($id, Request $request)
    {
        
        $project = Project::findOrFail($id);
        $project->update($request->all());

        Flash::message( 'Project updated!');

        return redirect('project');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        Project::destroy($id);

        Flash::message( 'Project deleted!');

        return redirect('project');
    }

}
