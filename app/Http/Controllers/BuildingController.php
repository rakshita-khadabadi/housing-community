<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Building;
use Exception;
use Illuminate\Support\Facades\DB;

class BuildingController extends Controller
{   
    function fetchAllBuildings(){
        
        return Building::all();
    }

    function getBuildingById($buildingId){

        return Building::find($buildingId);
    }

    function getBuildingByUserId($userId){
        return DB::table('buildings')->where('users_id','=',$userId)->get()->first(); 
    }

    function addNewBuilding($request, $buildingName, $subdivisionId, $userId){

        echo 'Inside addNewBuilding';
        // $roleController = new RoleController();
        // $adminUserId = $roleController->getAdminUserId();

        $building = new Building();
        $building->building_name = $buildingName;
        $building->occupancy_status = 'empty';
        $building->has_manager = 0;              // 0 means no manager
        $building->subdivisions_id = $subdivisionId;
        $building->users_id = $userId;

        try{
            $building->save();

            $buildingId = $building->id;
            
            $apartmentController = new ApartmentController();
            $apartmentController->addApartmentsToBuilding($request, $buildingId, $subdivisionId, $userId);

            $successMessage = 'Successfully added Building '.$buildingName;
            return redirect()->back()->with(['success'=> $successMessage]);
        }
        catch(Exception $e){

            $errorMessage = $e->getMessage();
            return redirect()->back()->with(['error'=> $errorMessage]);
        }

    }
}
