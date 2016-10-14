<?php

class DataSet{
	public $x;
	public $y;
	function __construct($x, $y){
		$this->x = $x;
		$this->y = $y;
	}
}

class Wrapper{
	public $set;
	public $limit;
	public $k;
}

function distance($p1, $p2){
	return abs($p1->x - $p2->x)+abs($p1->y - $p2->y);
}

function dump($table, $centroid, $k){
	$cluster = array();


				foreach($table as $row){

					$minValue = 999999;
					$minID = 0;

					for($i=0; $i<$k; $i++){
						$dist = distance($row, $centroid[$i]);
						if($minValue > $dist){
							$minID = $i;
							$minValue = $dist;
						}
					}

					$cluster[] = $minID;
				}

	return $cluster;
}

function dump_group($centroid, $group, $k){
				for($i=0; $i<$k; $i++){

					$x = 0;
					$y = 0;

					$c = 0;
					foreach($group[ $i ] as $set){
						$c++;
						$x += $set->x;
						$y += $set->y;
					}

					$x /= $c;
					$y /= $c;

					$centroid[$i] = new DataSet($x, $y);
				}
	return $centroid;
}

function kmeans($json){
	/*
	$json = '{"set":[
				{"x":185,"y":72},
				{"x":170,"y":56},
				{"x":168,"y":60},
				{"x":179,"y":68},
				{"x":182,"y":72},
				{"x":188,"y":77},
				{"x":180,"y":71},
				{"x":180,"y":70},
				{"x":183,"y":84},
				{"x":180,"y":88},
				{"x":180,"y":67},
				{"x":177,"y":76}
			],
			"limit": 10,
			"k": 2
			}';
	//*/

	$obj = json_decode($json);

	$k = 2;
	if(isset( $obj->k )){
		$k = $obj->k;
	}

	$table = array();
	foreach($obj->set as $row){
		$table[] = new DataSet($row->x, $row->y);
	}

	$centroid = array();
	for($i=0; $i<$k; $i++)
		$centroid[] = new DataSet($table[$i]->x, $table[$i]->y);


	$iteration_limit = 10;
	if(isset( $obj->limit )){
		$iteration_limit = $obj->limit;
	}

	for($iteration = 0; $iteration < $iteration_limit; $iteration++){

		$cluster = dump($table, $centroid, $k);

		$group = array();
		for($i=0; $i<$k; $i++){
			$group[] = array();
		}

		$i = 0;
		foreach($table as $row){
			$group[ $cluster[$i] ][] = new DataSet( $row->x, $row->y );
				$i++;
		}

		$new_centroid = dump_group($centroid, $group, $k);

		// CHECK CHANGED IN NEW CENTROID AND BREAK
		$flag = true;	//ASSUME SAME VALUES EXIST
		$i = 0;
		foreach($new_centroid as $g){
			if( $centroid[$i]->x != $new_centroid[$i]->x || $centroid[$i]->y != $new_centroid[$i]->y)
			{
				$flag = false;
				break;
			}
			$i++;
		}

		if($flag){
			break;
		}

		// COPY NEW_CENTROID TO CENTROID
		$i = 0;
		foreach($new_centroid as $g){
			$centroid[$i] = new DataSet( $g->x, $g->y );
			$i++;
		}
	}

	class Output{
		public $centroid;
		public $iteration;
		public $success;

		public function __construct(){
			$this->success = true;
		}
	}

	$output = new Output();

	if($flag){

	}
	else{
		$output->success = false;
	}

	$output->centroid = array();
	foreach($centroid as $g){
		$output->centroid[] = new DataSet($g->x, $g->y);
	}
	$output->iteration = $iteration;

	return json_encode( $output );
}

/*
$obj = new Wrapper();
$obj->k = 2;
$obj->limit = 10;
$obj->set = array();
$obj->set[] = new DataSet(10, 20);
$obj->set[] = new DataSet(10, 21);
$obj->set[] = new DataSet(30, 20);
$obj->set[] = new DataSet(35, 30);

echo kmeans( json_encode($obj) );
*/

?>