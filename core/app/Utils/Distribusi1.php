<?php

namespace App\Utils;

use App\Models\Pembelian;
use App\Models\Transaksi;
use App\Models\TransaksiBayar;
use App\Unit;
use App\Utils\HitungMatrix;
use Illuminate\Support\Facades\DB;

class Distribusi extends Util
{
    //--These are variables to be used by the functions of class--//
	public $savings;
	public $sortedSavings; //has a format as ["nodeA;nodeB"] = saving;
	public $routes = Array();
	public $client_nodes;
	private $client_demands;
	public $distance_matrix;
	private $truck_capacity;
	private $truck_possible = Array();
	private $trucks_assigned = Array();
	public $reassing_cost = 0;
	private $loop_count = 0;
	public $unsatisfied = Array();
	public $total_cost;

	function loadData($client_nodes,$client_demands,$distance_matrix,$truck_capacity){
		$this->client_nodes = $client_nodes;
		$this->client_demands = $client_demands;
		$this->distance_matrix = $distance_matrix;
		$this->truck_capacity = $truck_capacity;
	}

	function cwSavings(){
        //contains distances from base to node $i
        // Jarak Dari Gudang Ke setiap node $i
        $distance_from_base = Array();
        //Jarak Dari node $i ke gudang
		$distance_to_base = Array();
		foreach($this->client_nodes as $i){
			$distance_from_base [$i] = $this->distance_matrix[0][$i];
			$distance_to_base [$i] = $this->distance_matrix[$i][0];
        }

		foreach ($this->client_nodes as $i){
			$dDi = $distance_from_base[$i];
			$this->savings[$i] = Array(); //... just for clarity
			foreach ($this->client_nodes as $j){
				if($j==0 || $j==$i) continue; //skip depot and current node
				$dDj = $distance_from_base[$j];
				$diD = $distance_to_base[$i];
				$dij = $this->distance_matrix[$i][$j];
				$this->savings[$i][$j] = $diD + $dDj - $dij; //clarke and wright assymetric savings
			}
        }

		return true;
    }


	function pengurutan(){
        // Pengecekan Apakah data $this->savings adalah array
        if(!is_array($this->savings))
            return
        false;
		$auxArray = Array();
		foreach($this->savings as $i=>$links){
			foreach($links as $j=>$saving){
				$auxArray["$i;$j"]=$saving;
			}
		}
		arsort($auxArray);
		$this->sortedSavings = $auxArray;
        return true;
    }


    function cwroutes()
    {
		foreach($this->sortedSavings as $nodes => $arch){

			//Unset Semua Variabel;
			if(isset($node_j_in)) unset($node_j_in);
			if(isset($node_i_in)) unset($node_i_in);
			if(isset($combined)) unset($combined);

			$nodes = explode(';',$nodes);
            $i = $nodes[0]; $j = $nodes[1];

            //First step : search nodes in current routes
            // Langkah 1 : Mencari node yang terdapat di rute terdekat
            $found = false;
			foreach ($this->routes as $key => $value) {
                $found_i = in_array($i,$value['road']);
                $found_j = in_array($j,$value['road']);

				if($found_i || $found_j){
                    //Menentukan 1 Simpul Secara Langsung
                    $found = true;
                    //both nodes are allready conected in a route
                    // Dua node sudah di hubungkan jadi 1 rute
					if($found_i === true && $found_j === true){
                        // Apabila Rute berlawanan maka di batalkan
						if(isset($this->sortedSavings["$j;$i"])){
                            unset($this->sortedSavings["$j;$i"]);
                        }
						continue;
					}
					if($found_i){
                        $node_i_in = $key;
                    }
					if($found_j){
                        $node_j_in = $key;
                    }
				}
            }


            //Second step: both nodes founds then combine routes if nodes not interior
            // Langkah 2 : Kedua node sudah ditemukan kemudian di kombinasikan dengan rute jika node tidak
            // dd(isset($node_j_in));

            // dd($node_i_in);
			if(isset($node_j_in) && isset($node_i_in)){
				if(!$this->interiornode($j,$this->routes[$node_j_in]['road']) && !$this->interiornode($i,$this->routes[$node_i_in]['road'])){
					$chunked_road_i = array_slice($this->routes[$node_i_in]['road'],1,-1);
					$chunked_road_j = array_slice($this->routes[$node_j_in]['road'],1,-1);
					$ipos = array_search($i,$chunked_road_i);
					$jpos = array_search($j,$chunked_road_j);
                    $combined = array(0);

                    // Dimulari dari i dan di akhir menggunakan j
					if($ipos==0 && $jpos!=0){
						foreach ($chunked_road_j as $node) { $combined[]=$node;	}
						foreach ($chunked_road_i as $node) { $combined[]=$node;	}
                    }

                    // Dimulai dari j dan di akhir menggunakan i;
					elseif ($jpos==0 && $ipos!=0) {
						foreach ($chunked_road_i as $node) { $combined[]=$node;	}
						foreach ($chunked_road_j as $node) { $combined[]=$node;	}
					}
                    $combined[] = 0;
					if(count($combined)>2){

							$truck = $this->checkTrucks($combined,$node_j_in,$node_i_in);
                            if(!$truck)
                            {
                            // Kendaraan tidak Ditemukan untuk di rute
                            continue ;
                            }
							else{
                                //set route
								unset($this->routes[$node_i_in],$this->routes[$node_j_in]);
								$this->assignTruckToRoute($truck,$combined);
								if(isset($this->sortedSavings["$j;$i"])){
                                    unset($this->sortedSavings["$j;$i"]);
                                }//unset the opposite route
								continue;
							}
					}else{
                        continue;
                    } // isnt mergeable
				}
			}

			//Third step: one node was found, add if possible
			//case found node j
			else if(isset($node_j_in)){

				if(array_search($j,$this->routes[$node_j_in]['road']) == 1){ //i will be added only if j is the first visit of truck
					$chunked_road_j = array_slice($this->routes[$node_j_in]['road'],1,-1);
					$combined[] = 0;
					$combined[] = $i;
					$combined = array_merge($combined,$chunked_road_j);
					$combined[] = 0;
					//check retrictions
					// if($this->checkRestrictions($combined)){
						$truck = $this->checkTrucks($combined,$node_j_in);
						if($truck===false){
                            continue;
                            //no truck can be used for the route
                            // Tidak ada kendaraan yang bisa digunakan untuk rute ini
                        }else{
                            //Masukan Rute
							unset($this->routes[$node_j_in]);
							$this->assignTruckToRoute($truck,$combined);
							if(isset($this->sortedSavings["$j;$i"])){unset($this->sortedSavings["$j;$i"]); }//unset the opposite route
							continue;
							}
					// }
					// else{ continue; } //dosen't fit restricctions
				}
			}
			//case found node i
			elseif(isset($node_i_in)){
                //j will be added only if i is the last visit of truck
				if(array_search($i,$this->routes[$node_i_in]['road']) == count($this->routes[$node_i_in]['road'])-2){
					$chunked_road_j = array_slice($this->routes[$node_i_in]['road'],1,-1);
					$combined[] = 0;
					$combined = array_merge($combined,$chunked_road_j);
					$combined[] = $j;
					$combined[] = 0;

                    $truck = $this->checkTrucks($combined,$node_i_in);
                    if($truck===false) continue; //no truck can be used for the route
                    else{ //set route
                        unset($this->routes[$node_i_in]);
                        $this->assignTruckToRoute($truck,$combined);
                        if(isset($this->sortedSavings["$j;$i"])){unset($this->sortedSavings["$j;$i"]); }//unset the opposite route
                        continue;
                    }
				}
			}

			//Fourth step: if not found, create new route and continue
			if(!$found){
				$road = array(0,$i,$j,0);
				$truck = $this->checkTrucks($road);
				if($truck===false){
                    continue;
                }
				$this->assignTruckToRoute($truck,$road);
				if(isset($this->sortedSavings["$j;$i"])){unset($this->sortedSavings["$j;$i"]);}//unset the opposite route
				continue;
			}

        }
        // dd($this->client_nodes);
        //Fifth step: create single routes with all remaining client nodes
		foreach($this->client_nodes as $node){
			$found = false;
			foreach($this->routes as $index=>$value){
				if(in_array($node,$value['road'])){
                    $found=true;
                }
			}
			if(!$found){
				$road = array(0,$node,0);
				$truck = $this->checkTrucks($road);
				if($truck === false){
                    $this->unsatisfied[$node] = "Tidak ada kendaraan yang cukup dengan muatan dari pelanggan";
				}
				else{
					$this->assignTruckToRoute($truck,$road);
				}
			}
        }
		return true;
	}


	/*
	The parametric Clarke Wright savings includes two parameters g->[0,3] and f->[0,1] that improve solution number
	 */
	function cwParametricSavings($g=false,$f=true){
		if($g===false || $g<0 || $g>3) $g = rand(0,3);
		if($f===false || $f<0 || $f>1) $g = rand(0,1);
		$distance_from_base = Array(); //contains distances from base to node $i
		$distance_to_base = Array(); //contains distances from base to node $i
		foreach($this->client_nodes as $i){
			$distance_from_base [$i] = $this->distance_matrix[0][$i];
			$distance_to_base [$i] = $this->distance_matrix[$i][0];
		}

		foreach ($this->client_nodes as $i){
			$dDi = $distance_from_base[$i];
			$this->savings[$i] = Array(); //... just for clarity
			foreach ($this->client_nodes as $j){
				if($j==0 || $j==$i) continue; //skip depot and current node
				$dDj = $distance_from_base[$j];
				$dDi = $distance_from_base[$i];
				$diD = $distance_to_base[$i];
				$djD = $distance_to_base[$j];
				$dij = $this->distance_matrix[$i][$j];
				$this->savings[$i][$j] = $diD + $dDj - $g*$dij + $f*abs($dDi-$djD); //clarke and wright savings

			}
		}
		return true;

	}

	/*
	This function will check if the route fits the time window restriction.
	any other non-vehicular restriction can be added to the test at this point.
	 */
	function checkRestrictions($route){
		//time check per node, all comparison done in seconds
		$current_time = $this->timeToSeconds($this->start_hour);
		for($i=1 ; $i<count($route)-1 ; $i++){
			$travel_distance = $this->distance_matrix[$route[$i-1]][$route[$i]];
			$travel_time = ($travel_distance/$this->truck_speed)*3600;
			$current_time += $travel_time;
			$client_type = 'N';
			$max_time = $this->timeToSeconds($this->client_type_proprties[$client_type]['max_time']);
			$min_time = $this->timeToSeconds($this->client_type_proprties[$client_type]['min_time']);
			$service_time = $this->timeToSeconds($this->client_type_proprties[$client_type]['service_time']);

			if($current_time>$max_time) return false; //exceeds maximum time of delivery
			elseif($current_time<$min_time){
				//got there before openning therefor it has to wait
				$current_time = $min_time+$service_time;
			}
			else{
				$current_time += $service_time;
			}
		}
		return true;
	}

	//auxiliary functions for time conversion
		function toTimeString($seconds){
			$seconds = floor($seconds); //fuck the decimals!
			$hours = floor($seconds/3600);
			$minutes = floor(($seconds-$hours*3600)/60);
			$seconds -= $minutes*60 + $hours*3600;
	   		return $this->lz($hours).":".$this->lz($minutes).":".$this->lz($seconds);
		}

		function timeToSeconds($timeString){
			$time = explode(':',$timeString);
			if(count($time)<3) return false;
			return $time[0]*3600+$time[1]*60+$time[2];
		}

		// lz = leading zero
		function lz($num){
		    return (strlen($num) < 2) ? "0{$num}" : $num;
		}


	function checkTrucks($route, $node_i_in=false, $node_j_in=false){
		//get the list of trucks that might work for the route
		$possible = $this->getPossibleTrucks($route);
		//no truck can satisfy volume or weight of route's demand
		if(count($possible)<1) return false;

		else{
			//Case of merger - Release trucks of merged routes
			if($node_i_in!==false){
				unset($this->trucks_assigned[array_search($this->routes[$node_i_in]['truck'],$this->trucks_assigned)]);
			}
			if($node_j_in!==false){
				unset($this->trucks_assigned[array_search($this->routes[$node_j_in]['truck'],$this->trucks_assigned)]);
			}
			//check for non assigned solution
            $found = false;
            // dd($this->trucks_assigned);
			foreach ($possible as $truck) {
				if(!in_array($truck,$this->trucks_assigned)){
					$found = $truck;
					break;
				}
			}

			if($found!==false){
				return $found;
			}

			//no non assigned solution
			if($found===false){
				//MatrixFitAlgorithm uses a costum class for pseudo-matrix simple operations
				$matrixfit = $this->MatrixFitRoute($route);
				if($matrixfit!==false){
					foreach($matrixfit as $truck_id=>$route_id){
						if($route_id=="new") $found = $truck_id;
						else $this->assignTruckToRoute($truck_id,$route_id);
					}
				}
				if($found!==false) return $found;
			}

			//we'll get here only if all previous operation failed
			//Case of merger -- restore trucks to routes failed to be merged
			if($node_i_in!==false){
				$this->trucks_assigned[]= $this->routes[$node_i_in]['truck'];
			}
			if($node_j_in!==false){
				$this->trucks_assigned[]= $this->routes[$node_j_in]['truck'];
			}
			return false;
		}
	}

	//cleans deleted routes and creates possible for missing routes
	function refreshPosibleTruck(){
		foreach($this->truck_possible as $route_id=>$truck_list){
			if(!isset($this->routes[$route_id])) unset($this->truck_possible[$route_id]);
		}
		foreach($this->routes as $route_id=>$contents){
			if(!isset($this->truck_possible[$route_id])) $this->truck_possible[$route_id]=$this->getPossibleTrucks($contents['road']);
		}
		return true;
	}

	function getPossibleTrucks($route){
		$volume = 0;
		$weight = 0;
		foreach($route as $node){
            if($node==0)
            continue;
			$volume += $this->client_demands[$node]['total_volume_of_order'];
			$weight += $this->client_demands[$node]['bobot_pesanan'];
		}

		//create list of possible trucks for the order
		$possible = Array();
		$fit = Array();
		foreach($this->truck_capacity as $truck=>$capacity){
			if($capacity['weight'] >= $weight && $capacity['volume']>=$volume){
                // Menggunakan Perhitungan Berat dan Volume Kapasitas

                // Mengggunakan Volume Kapasitas saja
				$fit["".$truck] = ($volume/$capacity['volume']);
			}
        }
        // dd($fit);
		//order possible solution by tighter fit
		arsort($fit);
        $possible = array_keys($fit);
		return $possible;
	}

	// a series of matrix operations for determinign a possible solution
	function MatrixFitRoute($route) {
		// require_once('matrix.php');
		$this->refreshPosibleTruck();
		$route_possible_trucks = $this->getPossibleTrucks($route);
		$copy_possible_trucks = $this->truck_possible;

		$list_all_possible_trucks = Array();
		foreach ($copy_possible_trucks as $value) {
			$list_all_possible_trucks = array_merge($list_all_possible_trucks,$value);
		}
		$list_all_possible_trucks = array_unique(array_merge($list_all_possible_trucks,$route_possible_trucks));
		$pending_routes = array_keys($this->routes);
		array_push($pending_routes,"new");
		$assigned_trucks = Array();


		//create white_matrix
		$white_matrix = Array();
		foreach($list_all_possible_trucks as $truck_id){
			$white_matrix[$truck_id] = Array();
			foreach ($pending_routes as $route_id) {
				if($route_id=="new"){
					$white_matrix[$truck_id][$route_id] = (in_array($truck_id,$route_possible_trucks)) ? 1 : 0;
				}
				else{
					$white_matrix[$truck_id][$route_id] = (in_array($truck_id,$copy_possible_trucks[$route_id])) ? 1 : 0;
				}
			}
		}

		$grey_matrix = new HitungMatrix($white_matrix);


		//basic required condition for solution
		if(!$grey_matrix->possible()) return false;

		//cardinality==1 check for $route. this means that the route has only one truck that it can use.
		foreach($grey_matrix->getColumnKeys() as $route_id){
			if($grey_matrix->getColumnCardinal($route_id)==1){
				$truck_id = $grey_matrix->getFirstPositiveRow($route_id);
				if($truck_id===false) trigger_error("Looked for positive row but found none",E_USER_ERROR);
				else{
					$assigned_trucks[$truck_id] = $route_id;
					$grey_matrix->deleteRow($truck_id);
					$grey_matrix->deleteColumn($route_id);
				}
			}
		}
		//cardinality==1 check for $truck. this means that there is only one route that can use this truck
		foreach ($grey_matrix->getRowKeys() as $truck_id) {
			if($grey_matrix->getRowCardinal($truck_id)==1){
				$route_id = $grey_matrix->getFirstPositiveColumn($truck_id);
				if($route_id===false) trigger_error("Looked for positive col but found none",E_USER_ERROR);
				else{
					$assigned_trucks[$truck_id] = $route_id;
					$grey_matrix->deleteRow($truck_id);
					$grey_matrix->deleteColumn($route_id);
				}
			}
		}

		//trivial solution found
		if(count($grey_matrix->getColumnKeys())<1) return $assigned_trucks;
		//check again for basic condition before continuing
		if(!$grey_matrix->possible()) return false;

		//iterate while there are still routes to be fit, we will include exit statments under certain conditions inside the loop
		$black_matrix = new HitungMatrix($grey_matrix->getMatrix());
		$ignores = Array();
		$routes_ids = $black_matrix->getColumnKeys();
		$last_route_id = end($routes_ids);
		$first_route_id = reset($routes_ids);
		$max_loop = $black_matrix->maximumIterations();
		$loop_count = 0;
		while(true){
			if($loop_count>=$max_loop){
				trigger_error("loops count exceeded logical max loop, please check the code for errors",E_USER_ERROR);
			}
			$black_matrix = new HitungMatrix($grey_matrix->getMatrix()); //recreate for loop
			$routes_ids = $black_matrix->getColumnKeys(); //recreate for loop
			$assignments = Array(); // list of assignments for solution
			$previous_route_id=false;
			$previous_route_value=false;
			$n = 1; //for slice porpuses
			foreach($routes_ids as $route_id){
				if($black_matrix->possible()===false){
					return false;
				}
				if(count($black_matrix->getColumnKeys())==0 ||count($black_matrix->getRowKeys())==0){
					return false;
				}
				if(!isset($ignores[$route_id])) $ignores[$route_id]=Array();
				$fix_truck = $black_matrix->getFirstPositiveRow($route_id,$ignores[$route_id]);
				//there are no more positive values availables
				if($fix_truck===false){
					//no more subsitituions left
					if($route_id==$first_route_id){return false;}
					//previous fix dosent allow solutions. each time an ignore restriction is added to a node, all children node restricction must be deleted
					else{
						$previous_step = $previous_route_value;
						if($previous_step===false){

							trigger_error("something went wrong while searching for the previous positive of $previous_route_id",E_USER_ERROR);
						}
						else{array_push($ignores[$previous_route_id],$previous_step);}
						$ignores = array_slice($ignores,0,$n,TRUE);
						break;//restart loop
					}
				}
				//a positive value was found, so we fix and proceed
				else{
					$n += 1 ;
					$assignments[$fix_truck]=$route_id;
					$black_matrix->deleteRow($fix_truck);
					$black_matrix->deleteColumn($route_id);
					$previous_route_id = $route_id;
					$previous_route_value = $fix_truck;
					//A solution was found, hurray!
					if($route_id==$last_route_id){
						return array_merge($assigned_trucks,$assignments);
					}
				}
			}

		}
	}

	//if route is an array it will create a new route_id for it in routes. if its an int it will reassign the truck for that route.
	function assignTruckToRoute($truck,$route){
        // dd($route);
		if(is_array($route)){
			if(count($this->routes)<1) $future_position = 0;
			else{
				list($future_position) = array_reverse(array_keys($this->routes));
				$future_position += 1;
			}
			$this->routes[] = array('truck'=>$truck,'road'=>$route);
			$this->truck_possible[$future_position] = $this->getPossibleTrucks($route);
			$this->trucks_assigned[] = $truck;

		}
		else{
            if(!isset($this->routes[$route]))
            dd("trying to reassign truck to non-existent route",E_USER_ERROR);
			$old_truck = $this->routes[$route]['truck'];
			$this->truck_possible[$route] = $this->getPossibleTrucks($this->routes[$route]['road']); //recreate
			$this->trucks_assigned[] = $truck;
			if(!in_array($old_truck,$this->trucks_assigned)) dd("trying to remove a truck that was never assigned",E_USER_ERROR);
			unset($this->trucks_assigned[array_search($old_truck,$this->trucks_assigned)]);
			$this->routes[$route]['truck']=$truck;
        }
	}

	function getOpponents($possible_trucks){
		$opponents = Array();
		foreach($this->routes as $key => $values){
			if(in_array($values['truck'],$possible_trucks)) $opponents[]=$key;
		}
		return $opponents;

	}

	function interiornode($node,$road){
		$max = count($road,0);
		if($max==2) return false; // no interior between two values
		$search = array_search($node,$road);
		if($search===false) die('something went with the code at interior node');
		if($search==1||$search==$max-2) return false; // edge values
		else return true;
	}


	function finishingTouch(){
        $this->total_cost=0;
        // dd($this->client_demands);
		foreach($this->routes as $key=>$values){
			$total_distance = 0;
			// $total_time = $this->timeToSeconds($this->start_hour);
            $total_volume = 0;
            // dd($values['road']);
			for($i=1; $i<count($values['road']);$i++){
				$distance = $this->distance_matrix[$values['road'][$i-1]][$values['road'][$i]];
				if($values['road'][$i] != '0'){
                    $total_volume += $this->client_demands[$values['road'][$i]]['total_volume_of_order'];
                    $this->routes[$key]['pengiriman_id'][] = $this->client_demands[$values['road'][$i]]['pengiriman_id'];
				}
				// $total_distance += $distance;

            }

			// $this->routes[$key]['truck_max_weight'] = $this->truck_capacity[$this->routes[$key]['truck']]['weight'];
			// $this->routes[$key]['truck_max_volume'] = $this->truck_capacity[$this->routes[$key]['truck']]['volume'];
			// $this->routes[$key]['total_weight'] = $total_weight;
			$this->routes[$key]['total_volume'] = $total_volume;
			// $this->routes[$key]['total_time'] = $total_time." seconds";
			// $this->routes[$key]['time_back_at_depot'] = $this->toTimeString($total_time);
            // $this->total_cost += $total_time;
            // dd($this->routes);
		}
	}
}
