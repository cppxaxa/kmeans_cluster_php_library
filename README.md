# K-MEANS Clusters PHP Library
It is a simple PHP library to integrate and get clusters with implementation of kmeans

# Usage
You need to provide k value to get clusters.

$obj = new Wrapper();
$obj->k = 2;
$obj->limit = 10;
$obj->set = array();
$obj->set[] = new DataSet(10, 20);
$obj->set[] = new DataSet(10, 21);
$obj->set[] = new DataSet(30, 20);
$obj->set[] = new DataSet(35, 30);

echo kmeans( json_encode($obj) );
